<?php

namespace NS\ImportBundle\Controller;

use \NS\ImportBundle\Filter\Duplicate;
use \NS\ImportBundle\Filter\NotBlank;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sonata\CoreBundle\Exporter\Exporter;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\BinaryFileResponse;
use \Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
        $processor->setDuplicate(new Duplicate(array('getcode' => 'site', 1 => 'caseId'),$import->getDuplicateFile()));
        $processor->setNotBlank(new NotBlank(array('caseId', 'site')));
        $processor->setLimit(400);

        $result = $processor->process($import);
        $entityMgr->flush();

        $updater = $this->get('ns_import.importer.upload_handler');
        $updater->update($import, $result, $processor->getWriter($import->getClass())->getResults());

        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $entityMgr->persist($import);
        $entityMgr->flush();

        return $this->redirect($this->generateUrl('importIndex'));
    }

    /**
     * @Route("/download/{type}/{id}",name="importResultDownload",requirements={"type": "success|errors|warnings|source"})
     * @param $type
     * @param $id
     * @return BinaryFileResponse
     */
    public function resultDownloadAction($type, $id)
    {
        $res = $this->get('doctrine.orm.entity_manager')->getRepository('NSImportBundle:Import')->findForUser($this->getUser(), $id);
        if ($res) {
            $sourceFile = null;

            switch ($type) {
                case 'success':
                    $sourceFile = $res->getSuccessFile();
                    break;
                case 'errors':
                    $sourceFile = $res->getErrorFile();
                    break;
                case 'source':
                    $sourceFile = $res->getSourceFile();
                    break;
                case 'warnings':
                    $sourceFile = $res->getWarningFile();
                    break;
            }

            if($sourceFile) {
                BinaryFileResponse::trustXSendfileTypeHeader();
                $response = new BinaryFileResponse($sourceFile);
                $response->headers->set('Content-Type', 'text/plain');
                $response->setContentDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    $sourceFile->getFilename()
                );

                return $response;
            }
        }

        throw $this->createNotFoundException();
    }
}