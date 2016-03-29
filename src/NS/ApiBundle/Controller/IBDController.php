<?php

namespace NS\ApiBundle\Controller;

use \Nelmio\ApiDocBundle\Annotation as ApiDoc;
use \Symfony\Component\HttpFoundation\Request;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \FOS\RestBundle\Controller\Annotations as REST;

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
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Gets a case for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the case is not found"
     *   }
     * )
     *
     * @REST\View(serializerGroups={"api"})
     * @REST\Get("/{objId}",name="nsApiIbdGetCase")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case does not exist
     */
    public function getIbdCaseAction($objId)
    {
        return $this->getCase('ibd', $objId);
    }

    /**
     * Retrieves an IBD case lab by id. Most fields are returned, however some fields
     * if empty are excluded from the result set. For example the firstName and
     * lastName fields are only returned when there is data in them.
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Gets a case lab for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the case is not found"
     *   }
     * )
     *
     * @REST\View(templateVar="case",serializerGroups={"api"})
     * @REST\Get("/{objId}/lab",name="nsApiIbdGetLab")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case doees not exist
     */
    public function getIbdCaseLabAction($objId)
    {
        return $this->getLab('ibd_sitelab', $objId);
    }

    /**
     * Retrieves an IBD case RRL lab by id. Most fields are returned, however some 
     * fields if empty are excluded from the result set. For example the firstName
     * and lastName fields are only returned when there is data in them.
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Gets a case RRL lab for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the case is not found"
     *   }
     * )
     *
     * @REST\View(templateVar="case",serializerGroups={"api"})
     * @REST\Get("/{objId}/rrl",name="nsApiIbdGetRRL")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case doees not exist
     */
    public function getIbdCaseRRLAction($objId)
    {
        return $this->getLab('ibd_referencelab', $objId);
    }

    /**
     * Retrieves an IBD case NL lab by id. Most fields are returned, however some
     * fields if empty are excluded from the result set. For example the firstName
     * and lastName fields are only returned when there is data in them.
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Gets a case NL lab for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the case is not found"
     *   }
     * )
     *
     * @REST\View(templateVar="case",serializerGroups={"api"})
     * @REST\Get("/{objId}/nl",name="nsApiIbdGetNL")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case doees not exist
     */
    public function getIbdCaseNLAction($objId)
    {
        return $this->getLab('ibd_nationallab', $objId);
    }

    /**
     * Patch IBD Case
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Patch IBD case",
     *   input = "ibd",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         400 = "Bad Request",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/{objId}",name="nsApiIbdPatchCase")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     *
     * @return \FOS\RestBundle\View\View
     */
    public function patchIbdCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', 'ibd', 'NSSentinelBundle:IBD');
    }

    /**
     * Patch IBD Lab Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates lab data for an IBD case",
     *  input = "ibd_lab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/lab",name="nsApiIbdPatchLab")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function patchIbdLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', 'ibd_lab', 'NSSentinelBundle:IBD\SiteLab');
    }

    /**
     * Patch IBD RRL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates Reference Lab data for an IBD case",
     *  input = "ibd_referencelab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/rrl",name="nsApiIbdPatchRRL")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function patchIbdRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', 'ibd_referencelab', 'NSSentinelBundle:IBD\ReferenceLab');
    }

    /**
     * Patch IBD NL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates National Lab data for an IBD case",
     *  input = "ibd_nationallab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/nl",name="nsApiIbdPatchNL")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function patchIbdNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', 'ibd_nationallab', 'NSSentinelBundle:IBD\NationalLab');
    }

    /**
     * Put IBD Outcome Data
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put IBD Outcome data",
     *   input = "ibd_outcome",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/{objId}/outcome",name="nsApiIbdPatchOutcome")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     *
     * @return \FOS\RestBundle\View\View
     */
    public function patchIbdOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', 'ibd_outcome', 'NSSentinelBundle:IBD');
    }

    /**
     * Put IBD Case
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put IBD case",
     *   input = "ibd",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         400 = "Bad Request",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/{objId}",name="nsApiIbdPutCase")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putIbdCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', 'ibd', 'NSSentinelBundle:IBD');
    }

    /**
     * Put IBD Lab Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates lab data for an IBD case",
     *  input = "ibd_lab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Put("/{objId}/lab",name="nsApiIbdPutLab")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function putIbdLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', 'ibd_lab', 'NSSentinelBundle:IBD\SiteLab');
    }

    /**
     * Put IBD RRL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates RRL data for an IBD case",
     *  input = "ibd_referencelab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Put("/{objId}/rrl",name="nsApiIbdPutRRL")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function putIbdRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', 'ibd_referencelab', 'NSSentinelBundle:IBD\ReferenceLab');
    }

    /**
     * Put IBD NL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates NL data for an IBD case",
     *  input = "ibd_nationallab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Put("/{objId}/nl",name="nsApiIbdPutNL")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function putIbdNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', 'ibd_nationallab', 'NSSentinelBundle:IBD\NationalLab');
    }

    /**
     * Put IBD Outcome Data
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put IBD Outcome data",
     *   input = "ibd_outcome",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/{objId}/outcome",name="nsApiIbdPutOutcome")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putIbdOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', 'ibd_outcome', 'NSSentinelBundle:IBD');
    }

    /**
     * This method is used to create a new IBD case. This must be called prior to setting any data
     * on the case. Although there is a 'type' field, the api should only ever set the field to 1.
     * The case is created, the status code is 204 and the new case is specified in the returned
     * 'Location' header.
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Creates a new IBD case",
     *   input = "create_case",
     *   statusCodes = {
     *      201 = "Returned when the case is created"
     *  }
     * )
     *
     * @REST\Post("/",name="nsApiIbdPostCase")
     * @REST\View()
     *
     * @param Request $request the request object
     *
     * @return array|\FOS\RestBundle\View\View
     */
    public function postIbdCaseAction(Request $request)
    {
        return $this->postCase($request, 'nsApiIbdGetCase', 'create_case', 'NSSentinelBundle:IBD');
    }
}
