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
 * Description of IBDController
 *
 * @author gnat
 * @Route("/ibd")
 */
class IBDController extends CaseController
{
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
     * @REST\Get("/cases/{id}")
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
     * Put IBD Case
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Put IBD case",
     *   input = "ibd",
     *   statusCodes = {
     *         202 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/cases/{id}")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     *
     */
    public function putIbdCasesAction(Request $request, $id)
    {
        return $this->updateCase($request, 'ibd', 'NSSentinelBundle:Meningitis', $id);

    }

    /**
     * Put IBD Lab Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates lab data for an IBD case",
     *  input = "ibd_lab",
     *  statusCodes={
     *         202 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Put("/cases/{id}/lab")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     */
    public function putIbdLabAction(Request $request, $id)
    {
        return $this->updateLab($request, 'ibd_lab', 'NSSentinelBundle:IBD\Lab', $id);
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
     * @REST\Post("/cases")
     *
     * @param Request $request the request object
     *
    */
    public function postIbdCasesAction(Request $request)
    {
        return $this->postCase($request,'ibd','create_ibd','NSSentinelBundle:Meningitis');
    }
}
