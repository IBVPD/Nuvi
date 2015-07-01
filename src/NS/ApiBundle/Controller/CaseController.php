<?php

namespace NS\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\FOSRestController;
use \Doctrine\Common\Persistence\ObjectManager;
use \Symfony\Component\Form\FormInterface;

/**
 * Description of CaseController
 *
 * @author gnat
 */
class CaseController extends FOSRestController
{
    private function getObject($class, $objId, $method = 'find')
    {
        try {
            $repo = $this->get('doctrine.orm.entity_manager')->getRepository($class);
            if (method_exists($repo, $method)) {
                return call_user_func(array($repo, $method), $objId);
            }

            throw new NotFoundHttpException("System Error");
        }
        catch (NonExistentCase $e) {
            throw new NotFoundHttpException("This case does not exist or you are not allowed to retrieve it");
        }
    }

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
                $class = 'NSSentinelBundle:Rota\SiteLab';
                break;
            case 'rota_referencelab':
                $class = 'NSSentinelBundle:Rota\ReferenceLab';
                break;
            case 'rota_nationallab':
                $class = 'NSSentinelBundle:Rota\NationalLab';
                break;
            default:
                throw new NotFoundHttpException("Invalid type: $type");
        }

        return $this->getObject($class, $objId, 'findOrCreateNew');
    }

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

    private function updateObject(Request $request, ObjectManager $entityMgr, FormInterface $form)
    {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entityMgr->persist($form->getData());
            $entityMgr->flush();

            return true;
        }

        return false;
    }

    protected function updateCase(Request $request, $objId, $method, $formName, $className)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $obj       = $entityMgr->getRepository($className)->find($objId);
        $form      = $this->createForm($formName, $obj, array('method' => $method));

        return ($this->updateObject($request, $entityMgr, $form)) ?
            $this->view(null, Codes::HTTP_NO_CONTENT) :
            $this->view($form, Codes::HTTP_BAD_REQUEST);
    }

    protected function updateLab(Request $request, $objId, $method, $formName, $className)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $obj       = $entityMgr->getRepository($className)->findOrCreateNew($objId);
        $form      = $this->createForm($formName, $obj, array('method' => $method));

        return ($this->updateObject($request, $entityMgr, $form)) ?
            $this->view(null, Codes::HTTP_NO_CONTENT) :
            $this->view($form, Codes::HTTP_BAD_REQUEST);
    }

    protected function postCase(Request $request, $route, $formName, $className)
    {
        try {
            $form = $this->createForm($formName);
            $form->handleRequest($request);

            if (!$form->isValid()) {
                return $this->view($form, Codes::HTTP_BAD_REQUEST);
            }

            $entityMgr = $this->get('doctrine.orm.entity_manager');
            $caseId    = $form->get('caseId')->getData();
            $case      = $entityMgr->getRepository($className)->findOrCreate($caseId, null);

            if (!$case->getId()) {
                $site = ($form->has('site')) ? $form->get('site')->getData() : $this->get('ns.sentinel.sites')->getSite();
                $case->setSite($site);
            }

            $entityMgr->persist($case);
            $entityMgr->flush();

            return $this->routeRedirectView($route, array('objId' => $case->getId()));
        }
        catch (\Exception $e) {
            return array('exception' => $e->getMessage());
        }
    }
}