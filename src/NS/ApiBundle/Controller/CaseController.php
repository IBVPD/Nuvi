<?php

namespace NS\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use NS\SentinelBundle\Exceptions\NonExistentCaseException;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormInterface;

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
     * @param $type
     * @param $objId
     * @return mixed
     */
    protected function getLab($type, $objId)
    {
        switch ($type) {
            case 'ibd_sitelab':
                $class = 'NSSentinelBundle:IBD\SiteLab';
                break;
            case 'ibd_referencelab':
                $class = 'NSSentinelBundle:IBD\ReferenceLab';
                break;
            case 'ibd_nationallab':
                $class = 'NSSentinelBundle:IBD\NationalLab';
                break;
            case 'rota_sitelab':
                $class = 'NSSentinelBundle:RotaVirus\SiteLab';
                break;
            case 'rota_referencelab':
                $class = 'NSSentinelBundle:RotaVirus\ReferenceLab';
                break;
            case 'rota_nationallab':
                $class = 'NSSentinelBundle:RotaVirus\NationalLab';
                break;
            default:
                throw new NotFoundHttpException("Invalid type: $type");
        }

        return $this->getObject($class, $objId, 'findOrCreateNew');
    }

    /**
     * @param $type
     * @param $objId
     * @return mixed
     */
    protected function getCase($type, $objId)
    {
        switch ($type) {
            case 'ibd':
                $class = 'NSSentinelBundle:IBD';
                break;
            case 'rota':
                $class = 'NSSentinelBundle:RotaVirus';
                break;
            default:
                throw new NotFoundHttpException("Invalid type: $type");
        }

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
     * @return \FOS\RestBundle\View\View
     */
    protected function updateCase(Request $request, $objId, $method, $formName, $className)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $obj = $entityMgr->getRepository($className)->find($objId);
        $form = $this->createForm($formName, $obj, ['method' => $method]);

        return ($this->updateObject($request, $entityMgr, $form)) ?
            $this->view(null, Response::HTTP_NO_CONTENT) :
            $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param $objId
     * @param $method
     * @param $formName
     * @param $className
     * @return \FOS\RestBundle\View\View
     */
    protected function updateLab(Request $request, $objId, $method, $formName, $className)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $obj = $entityMgr->getRepository($className)->findOrCreateNew($objId);
        $form = $this->createForm($formName, $obj, ['method' => $method]);

        return ($this->updateObject($request, $entityMgr, $form)) ?
            $this->view(null, Response::HTTP_NO_CONTENT) :
            $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param $route
     * @param $formName
     * @param $className
     * @return array|\FOS\RestBundle\View\View
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
                $site = ($form->has('site')) ? $form->get('site')->getData() : $this->get('ns.sentinel.sites')->getSite();
                $case->setSite($site);
            }

            $entityMgr->persist($case);
            $entityMgr->flush();

            return $this->routeRedirectView($route, ['objId' => $case->getId()]);
        } catch (\Exception $e) {
            return ['exception' => $e->getMessage()];
        }
    }
}
