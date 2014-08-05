<?php

namespace NS\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Description of CaseController
 *
 * @author gnat
 */
class CaseController extends FOSRestController
{
    protected function getCase($type,$id)
    {
        try
        {
            switch($type)
            {
                case 'ibd':
                    $obj = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Meningitis')->find($id);
                    break;
                case 'rota':
                    $obj = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:RotaVirus')->find($id);
                    break;
                default:
                    throw new NotFoundHttpException("Invalid type: $type");
            }

            $v = new View();
            $v->setData(array('case'=>$obj));
            return $this->handleView($v);
        }
        catch(NonExistentCase $e)
        {
            throw new NotFoundHttpException("This case does not exist or you are not allowed to retrieve it");
        }
    }

    protected function updateCase(Request $request, $formName, $className, $id)
    {
        $em   = $this->get('ns.model_manager');
        $obj  = $em->getRepository($className)->find($id);
        $form = $this->createForm($formName, $obj);
        $data = $request->request->all();

        $form->submit($data[$formName]);

        if(!$form->isValid())
            return $form;

        $em->persist($form->getData());
        $em->flush();

        return $this->view(null, Codes::HTTP_ACCEPTED);
    }

    protected function updateLab(Request $request, $type, $class, $id)
    {
        $em   = $this->get('ns.model_manager');
        $obj  = $em->getRepository($class)->findOrCreateNew($id);
        $form = $this->createForm($type,$obj);

        $data = $request->request->all();
        $form->submit($data[$type]);

        if(!$form->isValid())
            return $form;

        $em->persist($form->getData());
        $em->flush();

        return $this->view(null, Codes::HTTP_ACCEPTED);

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
            $v = new View();
            $v->setData(array('exception'=>$e->getMessage()));

            return $this->handleView($v);
        }
    }
}
