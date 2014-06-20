<?php

namespace NS\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;

/**
 * Description of ApiController
 *
 * @author gnat
 * @Route("/api")
 */
class ApiController extends \FOS\RestBundle\Controller\FOSRestController
{
   /**
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
    * @REST\Get("/v1/ibd/cases/{id}")
    *
    * @param string  $id      the object id
    * @param string  $format  json|xml
    *
    * @return array
    *
    * @throws NotFoundHttpException when case not exist
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
    * @REST\Get("/v1/rota/cases/{id}")
    *
    * @param string  $id      the object id
    * @param string  $format  json|xml
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
                    $obj = $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:Meningitis')->find($id);
                    break;
                case 'rota':
                    $obj = $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus')->find($id);
                    break;
                default:
                    throw new NotFoundHttpException("Invalid type: $type");
            }

            $v = new View();
            $v->setData(array('case'=>$obj));

            return $this->handleView($v);
        }
        catch(NotFoundHttpException $e)
        {
            throw $e;
        }
        catch(\Exception $e)
        {
            throw new NotFoundHttpException("HERE??? Could not find $type with id:$id ".$e->getMessage());
        }
    }

    /**
     * Patch a Case,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Patch a new case",
     *   input = "ibd"
     * )
     *
     * @REST\Patch("/v1/ibd/cases/{id}")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $id
     */
    public function patchIbdCasesAction(Request $request, $id)
    {
        return $this->updateIbd($request,$id);
    }

    /**
     * Put a Case,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Patch a new case",
     *   input = "ibd"
     * )
     *
     * @REST\Put("/v1/ibd/cases/{id}")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $id
     */
    public function putIbdCasesAction(Request $request, $id)
    {
        return $this->updateIbd($request,$id);
    }

    private function updateIbd(Request $request, $id)
    {
        $em   = $this->get('doctrine.orm.entity_manager');
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
    * Create a Case,
    *
    * @ApiDoc(
    *   resource = true,
    *   description = "Creates a new case",
    *   input = "create_ibd"
    * )
    *
    * @REST\Post("/v1/ibd/cases")
    *
    * @param Request $request the request object
    * @param string  $format  json|xml
    *
    * @return array
    *
    * @throws NotFoundHttpException when case not exist
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
    *   description = "Creates a new case",
    *   input = "create_rota"
    * )
    *
    * @REST\Post("/v1/rota/cases")
    *
    * @param Request $request the request object
    * @param string  $format  json|xml
    *
    * @return array
    *
    * @throws NotFoundHttpException when case not exist
    */
    public function postRotaCasesAction(Request $request)
    {
        return $this->postCase($request,'rota');
    }

    private function postCase(Request $request, $type)
    {
        switch($type)
        {
            case 'ibd':
                $obj = new \NS\SentinelBundle\Entity\Meningitis();
                $form = $this->createForm('create_ibd',$obj);
                break;
            case 'rota':
                $obj = new \NS\SentinelBundle\Entity\RotaVirus();
                $form = $this->createForm('create_rotavirus',$obj);
                break;
            default:
                throw new NotFoundHttpException("No type? $type");
        }

        $form->submit($request->request->all());
        if($form->isValid())
        {
            $em  = $this->get('doctrine.orm.entity_manager');
            $obj = $form->getData();
            $em->persist($obj);
            $em->flush();

            $routeOptions = array('id' => $obj->getId(), '_format' => $request->get('_format'));

            return $this->routeRedirectView('ns_sentinel_api_get'.$type.'case', $routeOptions, Codes::HTTP_CREATED);
        }

        return $form;
    }

    /**
     * @REST\GET("/v1/test")
     */
    public function testAction()
    {
        $v = new View();
        $v->setData(array('username'=>$this->getUser()->getUsername(),'roles'=>$this->getUser()->getRoles()));

        return $this->handleView($v);
    }
}
