<?php

namespace NS\ImportBundle\Controller;

use NS\ImportBundle\Form\ImportSelectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
        $form      = $this->createForm(ImportSelectType::class, null, ['user' => $entityMgr->getReference('NS\SentinelBundle\Entity\User', $this->getUser()->getId())]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $queue = $this->get('ns_import.workqueue');
            $ret = $queue->submit($form->getData());
            if ($ret === true) {
                $this->get('ns_flash')->addSuccess(null, null, "Import Added");
            } else {
                $this->get('ns_flash')->addError(null, 'Unable to add import', $ret);
            }

            return $this->redirect($this->generateUrl('importIndex'));
        }

        $paginator  = $this->get('knp_paginator');
        $query      = $entityMgr->getRepository('NSImportBundle:Import')->getResultsForUser($this->getUser(), 'r');
        $pagination = $paginator->paginate($query, $request->query->get('page', 1), 10);

        return $this->render('NSImportBundle:Import:index.html.twig', ['form' => $form->createView(), 'results' => $pagination]);
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

        return new JsonResponse($percent);
    }

    /**
     * @param $id
     * @Route("/execute/{id}",name="importExecute",requirements={"id"="\d+"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function executeAction($id)
    {
        $worker = $this->get('ns_import.batch_worker');
        $worker->consume($id, 400);

        return $this->redirect($this->generateUrl('importIndex'));
    }

    /**
     * @param $id integer
     * @Route("/resubmit/{id}", name="importResubmit",requirements={"id"="\d+"})
     *
     * @return RedirectResponse
     */
    public function resubmitAction($id)
    {
        $import = $this->get('doctrine.orm.entity_manager')->find('NSImportBundle:Import', $id);
        $queue = $this->get('ns_import.workqueue');

        if ($queue->reSubmit($import)) {
            $this->get('ns_flash')->addSuccess(null, null, "Import Re-Submitted");
        } else {
            $this->get('ns_flash')->addWarning(null, 'Unable to re-submit import for background processing', 'There was an error communicating with the beanstalk server');
        }

        return $this->redirect($this->generateUrl('importIndex'));
    }

    /**
     * @param $id
     * @Route("/pause/{id}",name="importDelete",requirements={"id"="\d+"})
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $import = $this->get('doctrine.orm.entity_manager')->find('NSImportBundle:Import', $id);
        $queue = $this->get('ns_import.workqueue');
        $queue->delete($import);
        return $this->redirect($this->generateUrl('importIndex'));
    }

    /**
     * @param $id
     * @Route("/pause/{id}",name="importPause",requirements={"id"="\d+"})
     * @return RedirectResponse
     */
    public function pauseAction($id)
    {
        $import = $this->get('doctrine.orm.entity_manager')->find('NSImportBundle:Import', $id);
        $queue = $this->get('ns_import.workqueue');
        $queue->pause($import);
        return $this->redirect($this->generateUrl('importIndex'));
    }

    /**
     * @param $id
     * @Route("/resume/{id}",name="importResume",requirements={"id"="\d+"})
     * @return RedirectResponse
     */
    public function resumeAction($id)
    {
        $import = $this->get('doctrine.orm.entity_manager')->find('NSImportBundle:Import', $id);
        $queue = $this->get('ns_import.workqueue');
        $queue->resume($import);
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

            if ($sourceFile) {
                try {
                    BinaryFileResponse::trustXSendfileTypeHeader();
                    $response = new BinaryFileResponse($sourceFile);
                    $response->headers->set('Content-Type', 'text/plain');
                    $response->setContentDisposition(
                        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                        $sourceFile->getFilename()
                    );

                    return $response;
                } catch (FileException $exception) {
                    throw $this->createNotFoundException(sprintf('Unable to locate or read "%s" import result file',$sourceFile->getFilename()));
                }
            }
        }

        throw $this->createNotFoundException('Unable to find requested import result');
    }
}
