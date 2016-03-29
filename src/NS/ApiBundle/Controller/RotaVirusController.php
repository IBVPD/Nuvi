<?php

namespace NS\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as REST;

/**
 * Description of RotaVirusController
 *
 * @author gnat
 * @Route("/api/rota")
 */
class RotaVirusController extends CaseController
{
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
     * @REST\View(templateVar="case",serializerGroups={"api"})
     * @REST\Get("/{objId}",name="nsApiRotaGetCase")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
    */
    public function getRotaCaseAction($objId)
    {
        return $this->getCase('rota', $objId);
    }

    /**
     * Retrieves an RotaVirus case lab by id. Most fields are returned, however some fields
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
     * @REST\View(templateVar="case",serializerGroups={"api"})
     * @REST\Get("/{objId}/lab",name="nsApiRotaGetLab")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case doees not exist
    */
    public function getRotaCaseLabAction($objId)
    {
        return $this->getLab('rota_sitelab', $objId);
    }

    /**
     * Retrieves an RotaVirus case RRL by id. Most fields are returned, however some fields
     * if empty are excluded from the result set. For example the firstName and
     * lastName fields are only returned when there is data in them.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a case RRL for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the case is not found"
     *   }
     * )
     *
     * @REST\View(templateVar="case",serializerGroups={"api"})
     * @REST\Get("/{objId}/rrl",name="nsApiRotaGetRRL")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case doees not exist
     */
    public function getRotaCaseRRLAction($objId)
    {
        return $this->getLab('rota_referencelab', $objId);
    }

    /**
     * Retrieves an RotaVirus case NL by id. Most fields are returned, however some fields
     * if empty are excluded from the result set. For example the firstName and
     * lastName fields are only returned when there is data in them.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a case NL for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the case is not found"
     *   }
     * )
     *
     * @REST\View(templateVar="case",serializerGroups={"api"})
     * @REST\Get("/{objId}/nl",name="nsApiRotaGetNL")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case doees not exist
     */
    public function getRotaCaseNLAction($objId)
    {
        return $this->getLab('rota_nationallab', $objId);
    }

    /**
     * Patch RotaVirus Case
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Patch RotaVirus case",
     *   input = "rotavirus",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/{objId}",name="nsApiRotaPatchCase")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     *
     * @return \FOS\RestBundle\View\View
     */
    public function patchRotaCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', 'rotavirus', 'NSSentinelBundle:RotaVirus');
    }

    /**
     * Patch RotaVirus Lab Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus Lab data",
     *  input = "rotavirus_lab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/lab",name="nsApiRotaPatchLab")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function patchRotaLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', 'rotavirus_lab', 'NSSentinelBundle:Rota\SiteLab');
    }

    /**
     * Patch RotaVirus RRL Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus RRL data",
     *  input = "rotavirus_referencelab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/rrl",name="nsApiRotaPatchRRL")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function patchRotaRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', 'rotavirus_referencelab', 'NSSentinelBundle:Rota\ReferenceLab');
    }

    /**
     * Patch RotaVirus NL Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus NL data",
     *  input = "rotavirus_nationallab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/nl",name="nsApiRotaPatchNL")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function patchRotaNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', 'rotavirus_nationallab', 'NSSentinelBundle:Rota\NationalLab');
    }

    /**
     * Patch RotaVirus Outcome Data
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Patch RotaVirus Outcome data",
     *   input = "rotavirus_outcome",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/{objId}/outcome",name="nsApiRotaPatchOutcome")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     *
     * @return \FOS\RestBundle\View\View
     */
    public function patchRotaOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', 'rotavirus_outcome', 'NSSentinelBundle:RotaVirus');
    }

    /**
     * Put RotaVirus Case
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Put RotaVirus case",
     *   input = "rotavirus",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/{objId}",name="nsApiRotaPutCase")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putRotaCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', 'rotavirus', 'NSSentinelBundle:RotaVirus');
    }

    /**
     * Put RotaVirus Lab Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus Lab data",
     *  input = "rotavirus_lab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     *
     * @REST\Put("/{objId}/lab",name="nsApiRotaPutLab")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function putRotaLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', 'rotavirus_lab', 'NSSentinelBundle:Rota\SiteLab');
    }

    /**
     * Put RotaVirus RRL Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus RRL data",
     *  input = "rotavirus_referencelab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     *
     * @REST\Put("/{objId}/rrl",name="nsApiRotaPutRRL")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function putRotaRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', 'rotavirus_referencelab', 'NSSentinelBundle:Rota\ReferenceLab');
    }

    /**
     * Put RotaVirus NL Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus NL data",
     *  input = "rotavirus_nationallab",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     *
     * @REST\Put("/{objId}/nl",name="nsApiRotaPutNL")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     * @return \FOS\RestBundle\View\View
     */
    public function putRotaNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', 'rotavirus_nationallab', 'NSSentinelBundle:Rota\NationalLab');
    }

    /**
     * Put RotaVirus Outcome Data
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Put RotaVirus Outcome data",
     *   input = "rotavirus_outcome",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/{objId}/outcome",name="nsApiRotaPutOutcome")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $objId
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putRotaOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', 'rotavirus_outcome', 'NSSentinelBundle:RotaVirus');
    }

    /**
     * Create a RotaVirus Case,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Rotavirus case",
     *   input = "create_case"
     * )
     *
     * @REST\Post("/",name="nsApiRotaPostCase")
     * @REST\View()
     *
     * @param Request $request the request object
     * @return array|\FOS\RestBundle\View\View
     */
    public function postRotaCaseAction(Request $request)
    {
        return $this->postCase($request, 'nsApiRotaGetCase', 'create_case', 'NSSentinelBundle:RotaVirus');
    }
}
