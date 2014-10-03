<?php

namespace NS\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\FOSRestController;
use \Doctrine\Common\Persistence\ObjectManager;
use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use \Symfony\Component\Form\FormInterface;

/**
 * Description of CaseController
 *
 * @author gnat
 */
class CaseController extends FOSRestController
{
    private function getObject($class, $id)
    {
        try
        {
            return $this->get('ns.model_manager')->getRepository($class)->find($id);
        }
        catch(NonExistentCase $e)
        {
            throw new NotFoundHttpException("This case does not exist or you are not allowed to retrieve it");
        }
    }

    protected function getCase($type,$id)
    {
        switch($type)
        {
            case 'ibd':
                $class = 'NSSentinelBundle:IBD';
                break;
            case 'rota':
                $class = 'NSSentinelBundle:RotaVirus';
                break;
            default:
                throw new NotFoundHttpException("Invalid type: $type");
        }

        return $this->getObject($class, $id);
    }

    private function updateObject(Request $request, ObjectManager $entityMgr, FormInterface $form)
    {
        $form->handleRequest($request);

        if($form->isValid())
        {
            $entityMgr->persist($form->getData());
            $entityMgr->flush();

            return true;
        }
        else
            return false;
    }

    protected function updateCase(Request $request, $id, $method, $formName, $className, $route)
    {
        $entityMgr    = $this->get('ns.model_manager');
        $obj   = $entityMgr->getRepository($className)->find($id);
        $form  = $this->createForm($formName, $obj, array('method'=>$method));

        return ($this->updateObject($request, $entityMgr, $form)) ? $this->view(null, Codes::HTTP_ACCEPTED,array('Location'=>$route)) : $this->view($form, Codes::HTTP_BAD_REQUEST);
    }

    protected function updateLab(Request $request, $id, $method, $formName, $className, $route)
    {
        $entityMgr   = $this->get('ns.model_manager');
        $obj  = $entityMgr->getRepository($className)->find($id);
        $form = $this->createForm($formName, $obj, array('method'=>$method));

        return ($this->updateObject($request, $entityMgr, $form)) ? $this->view(null, Codes::HTTP_ACCEPTED,array('Location'=>$route)) : $this->view($form, Codes::HTTP_BAD_REQUEST);
    }

    protected function postCase(Request $request, $route, $formName, $className)
    {
        try
        {
            $form = $this->createForm($formName);
            $form->handleRequest($request);

            if(!$form->isValid())
                return $this->view($form, Codes::HTTP_BAD_REQUEST);

            $entityMgr     = $this->get('ns.model_manager');
            $caseId = $form->get('caseId')->getData();
            $case   = $entityMgr->getRepository($className)->findOrCreate($caseId,null);

            if(!$case->getId())
            {
                $site = ($form->has('site')) ? $form->get('site')->getData() : $this->get('ns.sentinel.sites')->getSite();
                $case->setSite($site);
            }

            $entityMgr->persist($case);
            $entityMgr->flush();

            return $this->routeRedirectView($route, array('id' => $case->getId()));
        }
        catch (\Exception $e)
        {
            return array('exception'=>$e->getMessage());
        }
    }
}
