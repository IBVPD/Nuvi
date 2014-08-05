<?php

namespace NS\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use NS\SentinelBundle\Exceptions\NonExistentCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Description of ApiController
 *
 * @author gnat
 * @Route("/api")
 */
class ApiController extends FOSRestController
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
