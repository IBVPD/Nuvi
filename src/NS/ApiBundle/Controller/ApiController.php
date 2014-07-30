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

/**
 * Description of ApiController
 *
 * @author gnat
 * @Route("/api")
 */
class ApiController extends \FOS\RestBundle\Controller\FOSRestController
{
    /**
     * Get Sites
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Gets the list of suppliers",
     *  statusCodes = { 200 = "Returned when succesful" }
     * )
     * 
     * @Rest\View(serializerGroups={"api"})
     * @Rest\Get("/sites")
     *
     * @return array
     */
    public function sitesAction()
    {
        $sites   = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Site')->findAll();
        $context = SerializationContext::create()->setGroups(array('api'));

        $v       = new View();
        $v->setSerializationContext($context);
        $v->setData(array('sites'=>$sites));

        return $this->handleView($v);
    }

    /**
     *
     * Get IBD Case
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a case for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the case is not found"
     *   }
     * )
     *
     * @REST\View(templateVar="case")
     * @REST\Get("/ibd/cases/{id}")
     *
     * @param string  $id      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCase when case doees not exist
    */
    public function getIbdCaseAction($id)
    {
        return $this->getCase('ibd',$id);
    }

    /**
     * Get RotaVirus Case
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a case for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the case is not found"
     *   }
     * )
     *
     * @REST\View(templateVar="case")
     * @REST\Get("/rota/cases/{id}")
     *
     * @param string  $id      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
    */
    public function getRotaCaseAction($id)
    {
        return $this->getCase('rota',$id);
    }

    private function getCase($type,$id)
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

    /**
     * Patch IBD Case,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Patch a IBD case",
     *   input = "ibd"
     * )
     *
     * @REST\Patch("/ibd/cases/{id}")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $id
     * 
     */
    public function patchIbdCasesAction(Request $request, $id)
    {
        return $this->updateIbd($request,$id);
    }

    /**
     * Put IBD Case,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Put IBD case",
     *   input = "ibd"
     * )
     *
     * @REST\Put("/ibd/cases/{id}")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $id
     * 
     */
    public function putIbdCasesAction(Request $request, $id)
    {
        return $this->updateIbd($request,$id);
    }

    private function updateIbd(Request $request, $id)
    {
        $em   = $this->get('ns.model_manager');
        $obj  = $em->getRepository('NSSentinelBundle:Meningitis')->find($id);
        $form = $this->createForm('ibd',$obj);

        $form->submit($request->request->all());
        if($form->isValid())
        {
            $obj = $form->getData();
            $em->persist($obj);
            $em->flush();

            $routeOptions = array('id' => $id, '_format' => $request->get('_format'));

            return $this->routeRedirectView('ns_sentinel_api_getibdcase', $routeOptions, Codes::HTTP_ACCEPTED);
        }

        return $form;
    }

    /**
     * Create IBD Case,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new IBD case",
     *   input = "create_ibd"
     * )
     *
     * @REST\Post("/ibd/cases")
     *
     * @param Request $request the request object
     *
    */
    public function postIbdCasesAction(Request $request)
    {
        return $this->postCase($request,'ibd');
    }

    /**
     * Create a RotaVirus Case,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Rotavirus case",
     *   input = "create_rotavirus"
     * )
     *
     * @REST\Post("/rota/cases")
     *
     * @param Request $request the request object
    */
    public function postRotaCasesAction(Request $request)
    {
        return $this->postCase($request,'rota');
    }

    private function postCase(Request $request, $type)
    {
        try
        {
            $em = $this->get('ns.model_manager');
            switch($type)
            {
                case 'ibd':
                    $form = $this->createForm('create_ibd');
                    $form->handleRequest($request);
                    if(!$form->isValid())
                        return $form;

                    $caseId = $form->get('caseId')->getData();
                    $case   = $em->getRepository('NSSentinelBundle:Meningitis')->findOrCreate($caseId,null);
                    break;
                case 'rota':
                    $form = $this->createForm('create_rotavirus');
                    $form->handleRequest($request);
                    if(!$form->isValid())
                        return $form;

                    $caseId = $form->get('caseId')->getData();
                    $case   = $em->getRepository('NSSentinelBundle:RotaVirus')->findOrCreate($caseId,null);
                    break;
                default:
                    throw new NotFoundHttpException("No type? $type");
            }

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

    /**
     * Api Test Action,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Test API Access over OAuth2",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the case is not found",
     *     401 = "Invalid credentials"
     *   }
     * )
     * @REST\GET("/test")
    */
    public function testAction()
    {
        $v = new View();
        $v->setData(array('username'=>$this->getUser()->getUsername(),
                          'roles'=>$this->getUser()->getRoles(),
                          'hasToken'=>($this->get('security.context')->getToken())?'Yes':'No'));

        return $this->handleView($v);
    }
}
