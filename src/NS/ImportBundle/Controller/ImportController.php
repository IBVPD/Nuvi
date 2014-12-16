<?php

namespace NS\ImportBundle\Controller;

use Exporter\Source\ArraySourceIterator;
use NS\ImportBundle\Entity\Result;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     */
    public function indexAction(Request $request)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $form = $this->createForm('ImportSelect');

        $form->handleRequest($request);
        if($form->isValid())
        {
            $importer = $this->get('ns_import.processor');
            $import   = $form->getData();
            $wresult  = $importer->process($import);
            $result   = new Result($import,$wresult);

            if($entityMgr->isOpen())
            {
                $u = $this->getUser();
                $result->setUser($entityMgr->getReference(get_class($u),$u->getId()));

                $entityMgr->persist($result);
                $entityMgr->flush($result);

                $this->get('ns_flash')->addSuccess(null, null, "Import completed");
            }
            else
            {
                $exceptions = $wresult->getExceptions();
                $exception  = end($exceptions);

                $this->get('ns_flash')->addError(null,"Import failed",($exception->getPrevious()) ? $exception->getPrevious()->getMessage():$exception->getMessage());
            }

            return $this->redirect($this->generateUrl('importIndex'));
        }

        $paginator  = $this->get('knp_paginator');
        $query      = $entityMgr->getRepository('NSImportBundle:Result')->getResultsForUser($this->getUser(),'r');
        $pagination = $paginator->paginate( $query, $request->query->get('page',1), 10 );

        return array('form'=>$form->createView(),'results'=>$pagination);
    }

    /**
     * @Route("/download/{type}/{id}",name="importResultDownload")
     */
    public function resultDownloadAction($type, $id)
    {
        $res = $this->get('doctrine.orm.entity_manager')->getRepository('NSImportBundle:Result')->findForUser($this->getUser(),$id);
        if($res)
        {
            $format = 'csv';

            switch ($type)
            {
                case 'success':
                    $source = new ArraySourceIterator($res->getSuccesses(), array(
                        'id', 'caseId', 'site', 'siteName'));
                    break;
                case 'errors':
                    $source = new ArraySourceIterator($res->getErrors(), array('row',
                        'column',
                        'message'));
                    break;
                case 'duplicates':
                    $source = new ArraySourceIterator($res->getDuplicateMessages(), array(
                        'row', 'message'));
                    break;
            }

            $filename = sprintf('export_%s_%s.%s', $type, date('Ymd_His'), $format);

            try
            {
                return $this->get('sonata.admin.exporter')->getResponse($format, $filename, $source);
            }
            catch (\Exception $excep)
            {
                die("I GOT AN EXCEPTION! " . $excep->getMessage());
            }
        }

        throw $this->createNotFoundException();
    }
}
