<?php

namespace NS\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as ApiDoc;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\IBD\NationalLab;
use NS\SentinelBundle\Entity\IBD\ReferenceLab;
use NS\SentinelBundle\Entity\IBD\SiteLab;
use NS\SentinelBundle\Exceptions\NonExistentCaseException;
use NS\SentinelBundle\Form\CreateType;
use NS\SentinelBundle\Form\IBD\CaseType;
use NS\SentinelBundle\Form\IBD\NationalLabType;
use NS\SentinelBundle\Form\IBD\OutcomeType;
use NS\SentinelBundle\Form\IBD\ReferenceLabType;
use NS\SentinelBundle\Form\IBD\SiteLabType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->getCase(IBD::class, $objId);
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
        return $this->getLab(SiteLab::class, $objId);
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
        return $this->getLab(ReferenceLab::class, $objId);
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
        return $this->getLab(NationalLab::class, $objId);
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
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchIbdCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', CaseType::class, IBD::class);
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
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchIbdLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', SiteLabType::class, SiteLab::class);
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
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchIbdRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', ReferenceLabType::class, ReferenceLab::class);
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
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function patchIbdNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', NationalLabType::class, NationalLab::class);
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
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function patchIbdOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', OutcomeType::class, IBD::class);
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
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putIbdCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', CaseType::class, IBD::class);
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
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putIbdLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', SiteLabType::class, SiteLab::class);
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
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putIbdRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', ReferenceLabType::class, ReferenceLab::class);
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
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putIbdNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', NationalLabType::class, NationalLab::class);
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
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putIbdOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', OutcomeType::class, IBD::class);
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
     * @return array|View
     */
    public function postIbdCaseAction(Request $request)
    {
        return $this->postCase($request, 'nsApiIbdGetCase', CreateType::class, IBD::class);
    }
}
