<?php

namespace NS\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as REST;

/**
 * Description of IBDController
 *
 * @author gnat
 * @Route("/api/ibd")
 */
class IBDController extends CaseController
{
    /**
     * Retrieves an IBD case by id. Most fields are returned, however some fields
     * if empty are excluded from the result set. For example the firstName and
     * lastName fields are only returned when there is data in them.
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
     * Retrieves an IBD case lab by id. Most fields are returned, however some fields
     * if empty are excluded from the result set. For example the firstName and
     * lastName fields are only returned when there is data in them.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a case lab for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the case is not found"
     *   }
     * )
     *
     * @REST\View(templateVar="case")
     * @REST\Get("/cases/{id}/lab")
     *
     * @param string  $id      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCase when case doees not exist
    */
    public function getIbdCaseLabAction($id)
    {
        return $this->getCaseLab('ibd',$id);
    }

    /**
     * Patch IBD Case
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Patch IBD case",
     *   input = "ibd",
     *   statusCodes = {
     *         202 = "Returned when successful",
     *         400 = "Bad Request",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/cases/{id}")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     *
     */
    public function patchIbdCasesAction(Request $request, $id)
    {
        return $this->updateCase($request, 'PATCH', 'ibd', 'NSSentinelBundle:Meningitis', $id);

    }

    /**
     * Patch IBD Lab Data,
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
     * @REST\Patch("/cases/{id}/lab")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     */
    public function patchIbdLabAction(Request $request, $id)
    {
        return $this->updateLab($request, 'PATCH','ibd_lab', 'NSSentinelBundle:IBD\Lab', $id);
    }

    /**
     * Put IBD Outcome Data
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Put IBD Outcome data",
     *   input = "ibd_outcome",
     *   statusCodes = {
     *         202 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/cases/{id}/outcome")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     *
     */
    public function patchIbdOutcomeAction(Request $request, $id)
    {
        return $this->updateCase($request, 'PATCH', 'ibd_outcome', 'NSSentinelBundle:Meningitis', $id);
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
     *         400 = "Bad Request",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/cases/{id}")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     *
     */
    public function putIbdCasesAction(Request $request, $id)
    {
        return $this->updateCase($request, 'PUT', 'ibd', 'NSSentinelBundle:Meningitis', $id);

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
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     */
    public function putIbdLabAction(Request $request, $id)
    {
        return $this->updateLab($request, 'PUT','ibd_lab', 'NSSentinelBundle:IBD\Lab', $id);
    }

    /**
     * Put IBD Outcome Data
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Put IBD Outcome data",
     *   input = "ibd_outcome",
     *   statusCodes = {
     *         202 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/cases/{id}/outcome")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     *
     */
    public function putIbdOutcomeAction(Request $request, $id)
    {
        return $this->updateCase($request, 'PUT', 'ibd_outcome', 'NSSentinelBundle:Meningitis', $id);
    }

    /**
     * This method is used to create a new IBD case. This must be called prior to setting any data
     * on the case. Although there is a 'type' field, the api should only ever set the field to 1.
     * The case is created, the status code is 202 and the new case is specified in the returned
     * 'Location' header.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new IBD case",
     *   input = "create_ibd",
     *   statusCodes = {
     *      202 = "Returned when the case is created"
     *  }
     * )
     *
     * @REST\Post("/cases")
     * @REST\View()
     *
     * @param Request $request the request object
     *
    */
    public function postIbdCasesAction(Request $request)
    {
        return $this->postCase($request,'ibd','create_ibd','NSSentinelBundle:Meningitis');
    }
}
