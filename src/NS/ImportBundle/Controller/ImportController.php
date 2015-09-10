<?php

namespace NS\ImportBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\BinaryFileResponse;
use \Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
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
     * @return array|RedirectResponse
     * @Method(methods={"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $form      = $this->createForm('ImportSelect', null, array('user' => $entityMgr->getReference('NS\SentinelBundle\Entity\User', $this->getUser()->getId())));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $entityMgr->getConnection()->beginTransaction();
            $import = $form->getData();
            try {
                $entityMgr->persist($import);
                $entityMgr->flush($import);

                $pheanstalk = $this->get("leezy.pheanstalk");
                $pheanstalk->useTube('import')->put($import->getId());
                $entityMgr->getConnection()->commit();

            } catch(\Pheanstalk_Exception_ConnectionException $excep) {
                $entityMgr->getConnection()->rollBack();
                @unlink($import->getSourceFile()->getPathname());
                @unlink($import->getWarningFile()->getPathname());
                @unlink($import->getErrorFile()->getPathname());
                @unlink($import->getSuccessFile()->getPathname());
                @unlink($import->getMessageFile()->getPathname());
                @unlink($import->getDuplicateFile()->getPathname());
                @rmdir($import->getSourceFile()->getPath());

                $this->get('ns_flash')->addError(null, 'Unable to add import', 'There was an error communicating with the beanstalk server');
                return $this->redirect($this->generateUrl('importIndex'));
            }

            $this->get('ns_flash')->addSuccess(null, null, "Import Added");
            return $this->redirect($this->generateUrl('importIndex'));
        }

        $paginator  = $this->get('knp_paginator');
        $query      = $entityMgr->getRepository('NSImportBundle:Import')->getResultsForUser($this->getUser(), 'r');
        $pagination = $paginator->paginate($query, $request->query->get('page', 1), 10);

        return $this->render('NSImportBundle:Import:index.html.twig',array('form' => $form->createView(), 'results' => $pagination));
    }

    /**
     * @param $id
     * @return Response
     *
     * @Route("/status/{id}",name="importStatus",requirements={"id"="\d+"})
     * @Method(methods={"GET"})
     */
    public function statusAction($id)
    {
        $percent = $this->get('doctrine.orm.entity_manager')->getRepository('NS\ImportBundle\Entity\Import')->getStatistics($id);

        return new Response(json_encode($percent));
    }

    /**
     * @param $id
     * @Route("/execute/{id}",name="importExecute")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function executeAction($id)
    {
        $worker = $this->get('ns_import.batch_worker');
        $worker->consume($id,400);

        return $this->redirect($this->generateUrl('importIndex'));
    }

    /**
     * @Route("/download/{type}/{id}",name="importResultDownload",requirements={"type": "success|errors|warnings|source"})
     * @param $type
     * @param $id
     * @Method(methods={"GET"})
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
