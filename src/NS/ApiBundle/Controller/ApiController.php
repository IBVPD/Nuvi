<?php

namespace NS\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Form\Types\Gender;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
    public function sitesAction(): array
    {
        $sites = $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:Site')->findAll();
        return ['sites' => $sites];
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
    public function testAction(): array
    {
        $mGender = new Gender(Gender::MALE);
        $ibd     = new IBD();
        $ibd->setGender($mGender);
        $ibd->setCaseId(rand(500, 10000));

        return [
            'username' => $this->getUser()->getUsername(),
            'roles'    => $this->getUser()->getRoles(),
            'hasToken' => $this->get('security.token_storage')->getToken() ? 'Yes' : 'No',
            'gender'   => $mGender,
            'ibd'      => $ibd,
        ];
    }
}
