<?php

namespace NS\ApiBundle\Controller;

use \Nelmio\ApiDocBundle\Annotation as ApiDoc;
use NS\SentinelBundle\Exceptions\NonExistentCaseException;
use \Symfony\Component\HttpFoundation\Request;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \FOS\RestBundle\Controller\Annotations as REST;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @throws NonExistentCaseException when case does not exist
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
     * @throws NonExistentCaseException when case does not exist
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
     * @throws NonExistentCaseException when case does not exist
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
     *   input = "NS\SentinelBundle\Form\IBD\CaseType",
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
        return $this->updateCase($request, $objId, 'PATCH', 'NS\SentinelBundle\Form\IBD\CaseType', 'NSSentinelBundle:IBD');
    }

    /**
     * Patch IBD Lab Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates lab data for an IBD case",
     *  input = "NS\SentinelBundle\Form\IBD\SiteLabType",
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
        return $this->updateLab($request, $objId, 'PATCH', 'NS\SentinelBundle\Form\IBD\SiteLabType', 'NSSentinelBundle:IBD\SiteLab');
    }

    /**
     * Patch IBD RRL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates Reference Lab data for an IBD case",
     *  input = "NS\SentinelBundle\Form\IBD\ReferenceLabType",
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
        return $this->updateLab($request, $objId, 'PATCH', 'NS\SentinelBundle\Form\IBD\ReferenceLabType', 'NSSentinelBundle:IBD\ReferenceLab');
    }

    /**
     * Patch IBD NL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates National Lab data for an IBD case",
     *  input = "NS\SentinelBundle\Form\IBD\NationalLabType",
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
        return $this->updateLab($request, $objId, 'PATCH', 'NS\SentinelBundle\Form\IBD\NationalLabType', 'NSSentinelBundle:IBD\NationalLab');
    }

    /**
     * Put IBD Outcome Data
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put IBD Outcome data",
     *   input = "NS\SentinelBundle\Form\IBD\OutcomeType",
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
        return $this->updateCase($request, $objId, 'PATCH', 'NS\SentinelBundle\Form\IBD\OutcomeType', 'NSSentinelBundle:IBD');
    }

    /**
     * Put IBD Case
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put IBD case",
     *   input = "NS\SentinelBundle\Form\IBD\CaseType",
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
        return $this->updateCase($request, $objId, 'PUT', 'NS\SentinelBundle\Form\IBD\CaseType', 'NSSentinelBundle:IBD');
    }

    /**
     * Put IBD Lab Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates lab data for an IBD case",
     *  input = "NS\SentinelBundle\Form\IBD\SiteLabType",
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
        return $this->updateLab($request, $objId, 'PUT', 'NS\SentinelBundle\Form\IBD\SiteLabType', 'NSSentinelBundle:IBD\SiteLab');
    }

    /**
     * Put IBD RRL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates RRL data for an IBD case",
     *  input = "NS\SentinelBundle\Form\IBD\ReferenceLabType",
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
        return $this->updateLab($request, $objId, 'PUT', 'NS\SentinelBundle\Form\IBD\ReferenceLabType', 'NSSentinelBundle:IBD\ReferenceLab');
    }

    /**
     * Put IBD NL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates NL data for an IBD case",
     *  input = "NS\SentinelBundle\Form\IBD\NationalLabType",
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
        return $this->updateLab($request, $objId, 'PUT', 'NS\SentinelBundle\Form\IBD\NationalLabType', 'NSSentinelBundle:IBD\NationalLab');
    }

    /**
     * Put IBD Outcome Data
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put IBD Outcome data",
     *   input = "NS\SentinelBundle\Form\IBD\OutcomeType",
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
        return $this->updateCase($request, $objId, 'PUT', 'NS\SentinelBundle\Form\IBD\OutcomeType', 'NSSentinelBundle:IBD');
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
     *   input = "NS\SentinelBundle\Form\CreateType",
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
        return $this->postCase($request, 'nsApiIbdGetCase', 'NS\SentinelBundle\Form\CreateType', 'NSSentinelBundle:IBD');
    }
}
