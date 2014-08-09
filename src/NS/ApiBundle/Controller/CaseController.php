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

    protected function getCaseLab($type,$id)
    {
        try
        {
            switch($type)
            {
                case 'ibd':
                    $class ='NSSentinelBundle:IBD\Lab';
                    break;
                case 'rota':
                    $class = 'NSSentinelBundle:Rota\Lab';
                    break;
                default:
                    throw new BadRequestHttpException(sprintf("Invalid request type: %s",$type));
            }

            return $this->getObject($class, $id);
        }
        catch (NotFoundHttpException $ex)
        {
            throw new NotFoundHttpException("Lab does not exist",$ex);
        }
    }

    protected function getCase($type,$id)
    {
        switch($type)
        {
            case 'ibd':
                $class = 'NSSentinelBundle:Meningitis';
                break;
            case 'rota':
                $class = 'NSSentinelBundle:RotaVirus';
                break;
            default:
                throw new NotFoundHttpException("Invalid type: $type");
        }

        return $this->getObject($class, $id);
    }

    private function updateObject(Request $request, ObjectManager $em, FormInterface $form)
    {
        $form->handleRequest($request);

        if($form->isValid())
        {
            $em->persist($form->getData());
            $em->flush();

            return true;
        }
        else
            return false;
    }

    protected function updateCase(Request $request, $id, $method, $formName, $className, $route)
    {
        $em    = $this->get('ns.model_manager');
        $obj   = $em->getRepository($className)->find($id);
        $form  = $this->createForm($formName, $obj, array('method'=>$method));

        return ($this->updateObject($request, $em, $form)) ? $this->view(null, Codes::HTTP_ACCEPTED,array('Location'=>$route)) : $this->view($form, Codes::HTTP_BAD_REQUEST);
    }

    protected function updateLab(Request $request, $id, $method, $formName, $className, $route)
    {
        $em   = $this->get('ns.model_manager');
        $obj  = $em->getRepository($className)->findOrCreateNew($id);
        $form = $this->createForm($formName, $obj, array('method'=>$method));

        return ($this->updateObject($request, $em, $form)) ? $this->view(null, Codes::HTTP_ACCEPTED,array('Location'=>$route)) : $this->view($form, Codes::HTTP_BAD_REQUEST);
    }

    protected function postCase(Request $request, $route, $formName, $className )
    {
        try
        {
            $form = $this->createForm($formName);
            $form->handleRequest($request);

            if(!$form->isValid())
                return $this->view($form, Codes::HTTP_BAD_REQUEST);

            $em     = $this->get('ns.model_manager');
            $caseId = $form->get('caseId')->getData();
            $case   = $em->getRepository($className)->findOrCreate($caseId,null);

            if(!$case->getId())
            {
                $site = ($form->has('site')) ? $form->get('site')->getData() : $this->get('ns.sentinel.sites')->getSite();
                $case->setSite($site);
            }

            $em->persist($case);
            $em->flush();

            return $this->routeRedirectView($route, array('id' => $case->getId()));
        }
        catch (\Exception $e)
        {
            return array('exception'=>$e->getMessage());
        }
    }
}
