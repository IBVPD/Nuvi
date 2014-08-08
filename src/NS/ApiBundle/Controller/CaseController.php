<?php

namespace NS\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\FOSRestController;
use \Doctrine\Common\Persistence\ObjectManager;
use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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

    private function updateObject(Request $request, ObjectManager $em, $obj, $method, $formName)
    {
        $form = $this->createForm($formName, $obj, array('method'=>$method));
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            if(!$form->isValid())
                return $form;

            $obj = $form->getData();
            $em->persist($form->getData());
            $em->flush();

            return $this->view(null, Codes::HTTP_ACCEPTED);
        }
        else
            return $this->view($form, Codes::HTTP_BAD_REQUEST);
    }

    protected function updateCase(Request $request, $method, $formName, $className, $id)
    {
        $em   = $this->get('ns.model_manager');
        $obj  = $em->getRepository($className)->find($id);

        return $this->updateObject($request, $em, $obj, $method, $formName);
    }

    protected function updateLab(Request $request, $method, $formName, $className, $id)
    {
        $em   = $this->get('ns.model_manager');
        $obj  = $em->getRepository($className)->findOrCreateNew($id);

        return $this->updateObject($request, $em, $obj, $method, $formName);
    }

    protected function postCase(Request $request, $type, $formName, $className)
    {
        try
        {
            $em   = $this->get('ns.model_manager');
            $form = $this->createForm($formName);
            $form->handleRequest($request);
            if(!$form->isValid())
                return $form;

            $caseId = $form->get('caseId')->getData();
            $case   = $em->getRepository($className)->findOrCreate($caseId,null);

            if(!$case->getId())
            {
                $site = ($form->has('site')) ? $form->get('site')->getData() : $this->get('ns.sentinel.sites')->getSite();
                $case->setSite($site);
            }

            $em->persist($case);
            $em->flush();
            $routeOptions = array('id' => $case->getId(), '_format' => $request->get('_format'));

            return $this->routeRedirectView('ns_api_api_get'.$type.'case', $routeOptions, Codes::HTTP_CREATED);

        }
        catch (\Exception $e)
        {
            return array('exception'=>$e->getMessage());
        }
    }
}
