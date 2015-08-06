<?php

namespace NS\ImportBundle\Controller;

use \Exporter\Source\ArraySourceIterator;
use \NS\ImportBundle\Filter\Duplicate;
use \NS\ImportBundle\Filter\NotBlank;
use \NS\ImportBundle\Importer\ImportResultUpdater;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sonata\CoreBundle\Exporter\Exporter;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Description of ImportController
 *
 * @author gnat
 * @Route("/{_locale}/import")
 */
class ImportController extends Controller
{
    /**
     * @param Request $request
     * @Route("/",name="importIndex")
     * @Template()
     * @return array|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $form      = $this->createForm('ImportSelect', null, array('user' => $entityMgr->getReference('NS\SentinelBundle\Entity\User', $this->getUser()->getId())));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $import = $form->getData();
            $entityMgr->persist($import);
            $entityMgr->flush($import);

            $this->get('ns_flash')->addSuccess(null, null, "Import Added");
            return $this->redirect($this->generateUrl('importIndex'));
        }

        $paginator  = $this->get('knp_paginator');
        $query      = $entityMgr->getRepository('NSImportBundle:Import')->getResultsForUser($this->getUser(), 'r');
        $pagination = $paginator->paginate($query, $request->query->get('page', 1), 10);

        return array('form' => $form->createView(), 'results' => $pagination);
    }

    /**
     * @param $id
     * @Route("/execute/{id}",name="importExecute")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function executeAction($id)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $import = $entityMgr->getRepository('NSImportBundle:Import')->find($id);

        $processor = $this->get('ns_import.processor');
        $processor->setDuplicate(new Duplicate(array('getcode' => 'site', 1 => 'caseId')));
        $processor->setNotBlank(new NotBlank(array('caseId', 'site')));
        $processor->setLimit(100);

        $result = $processor->process($import);
        $updater = $this->get('ns_import.importer.upload_handler');
        $updater->update($import, $result, $processor->getWriter($import->getClass())->getResults());

//        die(sprintf('Pre: %d Post: %d',$prePosition,$import->getPosition()));

        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $entityMgr->persist($import);
        $entityMgr->flush();

        return $this->redirect($this->generateUrl('importIndex'));
    }

    /**
     * @Route("/download/{type}/{id}",name="importResultDownload",requirements={"type": "success|errors|warnings|source"})
     * @param $type
     * @param $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function resultDownloadAction($type, $id)
    {
        $res = $this->get('doctrine.orm.entity_manager')->getRepository('NSImportBundle:Import')->findForUser($this->getUser(), $id);
        if ($res) {
            $format = 'csv';

            switch ($type) {
                case 'success':
                    $source = new ArraySourceIterator($res->getSuccesses(), array('id', 'caseId', 'site', 'siteName'));
                    break;
                case 'errors':
                    $source = new ArraySourceIterator($res->getErrors(), array('row','column', 'message'));
                    break;
                case 'duplicates':
                    $source = new ArraySourceIterator($res->getDuplicateMessages(), array('row', 'message'));
                    break;
            }

            $filename = sprintf('export_%s_%s.%s', $type, date('Ymd_His'), $format);
            $exporter = new Exporter();

            try {
                return $exporter->getResponse($format, $filename, $source);
            } catch (\Exception $excep) {
                die("I GOT AN EXCEPTION! " . $excep->getMessage());
            }
        }

        throw $this->createNotFoundException();
    }
}