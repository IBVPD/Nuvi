<?php

namespace NS\ApiBundle\Controller;

use \Nelmio\ApiDocBundle\Annotation\ApiDoc;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \FOS\RestBundle\Controller\Annotations as REST;
use \FOS\RestBundle\Controller\FOSRestController;

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
        $sites = $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:Site')->findAll();
        return array('sites' => $sites);
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
     * @Rest\View(serializerGroups={"api"})
     */
    public function testAction()
    {
        $mGender = new \NS\SentinelBundle\Form\Types\Gender(\NS\SentinelBundle\Form\Types\Gender::MALE);
        $ibd     = new \NS\SentinelBundle\Entity\IBD();
        $ibd->setGender($mGender);
        $ibd->setCaseId(rand(500, 10000));

        return array(
            'username' => $this->getUser()->getUsername(),
            'roles'    => $this->getUser()->getRoles(),
            'hasToken' => ($this->get('security.token_storage')->getToken()) ? 'Yes' : 'No',
            'gender'   => $mGender,
            'ibd'      => $ibd,
        );
    }

}
