<?php

namespace NS\ApiBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use NS\SentinelBundle\Exceptions\NonExistentCaseException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Description of CaseController
 *
 * @author gnat
 */
class CaseController extends FOSRestController
{
    /**
     * @param $class
     * @param $objId
     * @param string $method
     * @return mixed
     */
    private function getObject($class, $objId, $method = 'find')
    {
        try {
            $repo = $this->get('doctrine.orm.entity_manager')->getRepository($class);
            if (method_exists($repo, $method)) {
                return call_user_func([$repo, $method], $objId);
            }

            throw new NotFoundHttpException("System Error");
        } catch (NonExistentCaseException $e) {
            throw new NotFoundHttpException("This case does not exist or you are not allowed to retrieve it");
        }
    }

    /**
     * @param $class
     * @param $objId
     * @return mixed
     */
    protected function getLab($class, $objId)
    {
        return $this->getObject($class, $objId, 'findOrCreateNew');
    }

    /**
     * @param $class
     * @param $objId
     * @return mixed
     */
    protected function getCase($class, $objId)
    {
        return $this->getObject($class, $objId, 'find');
    }

    /**
     * @param Request $request
     * @param ObjectManager $entityMgr
     * @param FormInterface $form
     * @return bool
     */
    private function updateObject(Request $request, ObjectManager $entityMgr, FormInterface $form)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityMgr->persist($form->getData());
            $entityMgr->flush();

            return true;
        }

        return false;
    }

    /**
     * @param Request $request
     * @param $objId
     * @param $method
     * @param $formName
     * @param $className
     * @return View
     */
    protected function updateCase(Request $request, $objId, $method, $formName, $className)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $obj = $entityMgr->getRepository($className)->find($objId);
        $form = $this->createForm($formName, $obj, ['method' => $method]);

        return $this->updateObject($request, $entityMgr, $form) ?
            $this->view(null, Response::HTTP_NO_CONTENT) :
            $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param $objId
     * @param $method
     * @param $formName
     * @param $className
     * @return View
     */
    protected function updateLab(Request $request, $objId, $method, $formName, $className)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $obj = $entityMgr->getRepository($className)->findOrCreateNew($objId);
        $form = $this->createForm($formName, $obj, ['method' => $method]);

        return $this->updateObject($request, $entityMgr, $form) ?
            $this->view(null, Response::HTTP_NO_CONTENT) :
            $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param $route
     * @param $formName
     * @param $className
     *
     * @return array|View
     */
    protected function postCase(Request $request, $route, $formName, $className)
    {
        try {
            $form = $this->createForm($formName);
            $form->handleRequest($request);

            if ($form->isSubmitted() && !$form->isValid()) {
                return $this->view($form, Response::HTTP_BAD_REQUEST);
            }

            $entityMgr = $this->get('doctrine.orm.entity_manager');
            $caseId = $form->get('caseId')->getData();
            $site = $form->has('site') ? $form->get('site')->getData() : $this->get('ns.sentinel.sites')->getSite();

            $case = $entityMgr->getRepository($className)->findOrCreate($caseId, $site, null);

            if (!$case->getId()) {
                $site = $form->has('site') ? $form->get('site')->getData() : $this->get('ns.sentinel.sites')->getSite();
                $case->setSite($site);
            }

            $entityMgr->persist($case);
            $entityMgr->flush();

            return $this->routeRedirectView($route, ['objId' => $case->getId()]);
        } catch (Exception $e) {
            return ['exception' => $e->getMessage()];
        }
    }
}
