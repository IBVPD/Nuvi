<?php

namespace NS\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as ApiDoc;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Meningitis\NationalLab;
use NS\SentinelBundle\Entity\Meningitis\ReferenceLab;
use NS\SentinelBundle\Entity\Meningitis\SiteLab;
use NS\SentinelBundle\Exceptions\NonExistentCaseException;
use NS\SentinelBundle\Form\CreateType;
use NS\SentinelBundle\Form\Meningitis\CaseType;
use NS\SentinelBundle\Form\Meningitis\NationalLabType;
use NS\SentinelBundle\Form\Meningitis\OutcomeType;
use NS\SentinelBundle\Form\Meningitis\ReferenceLabType;
use NS\SentinelBundle\Form\Meningitis\SiteLabType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Description of MeningitisController
 *
 * @author gnat
 * @Route("/api/meningitis")
 */
class MeningitisController extends CaseController
{
    /**
     * Retrieves an Meningitis case by id. Most fields are returned, however some fields
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
     * @REST\Get("/{objId}",name="nsApiMeningitisGetCase")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case does not exist
     */
    public function getMeningitisCaseAction($objId)
    {
        return $this->getCase(Meningitis::class, $objId);
    }

    /**
     * Retrieves an Meningitis case lab by id. Most fields are returned, however some fields
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
     * @REST\Get("/{objId}/lab",name="nsApiMeningitisGetLab")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case does not exist
     */
    public function getMeningitisCaseLabAction($objId)
    {
        return $this->getLab(SiteLab::class, $objId);
    }

    /**
     * Retrieves an Meningitis case RRL lab by id. Most fields are returned, however some 
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
     * @REST\Get("/{objId}/rrl",name="nsApiMeningitisGetRRL")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case does not exist
     */
    public function getMeningitisCaseRRLAction($objId)
    {
        return $this->getLab(ReferenceLab::class, $objId);
    }

    /**
     * Retrieves an Meningitis case NL lab by id. Most fields are returned, however some
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
     * @REST\Get("/{objId}/nl",name="nsApiMeningitisGetNL")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case does not exist
     */
    public function getMeningitisCaseNLAction($objId)
    {
        return $this->getLab(NationalLab::class, $objId);
    }

    /**
     * Patch Meningitis Case
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Patch Meningitis case",
     *   input = "NS\SentinelBundle\Form\Meningitis\CaseType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         400 = "Bad Request",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/{objId}",name="nsApiMeningitisPatchCase")
     * @REST\View()
     *
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchMeningitisCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', CaseType::class, Meningitis::class);
    }

    /**
     * Patch Meningitis Lab Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates lab data for an Meningitis case",
     *  input = "NS\SentinelBundle\Form\Meningitis\SiteLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/lab",name="nsApiMeningitisPatchLab")
     * @REST\View()
     *
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchMeningitisLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', SiteLabType::class, SiteLab::class);
    }

    /**
     * Patch Meningitis RRL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates Reference Lab data for an Meningitis case",
     *  input = "NS\SentinelBundle\Form\Meningitis\ReferenceLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/rrl",name="nsApiMeningitisPatchRRL")
     * @REST\View()
     *
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchMeningitisRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', ReferenceLabType::class, ReferenceLab::class);
    }

    /**
     * Patch Meningitis NL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates National Lab data for an Meningitis case",
     *  input = "NS\SentinelBundle\Form\Meningitis\NationalLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/nl",name="nsApiMeningitisPatchNL")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function patchMeningitisNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', NationalLabType::class, NationalLab::class);
    }

    /**
     * Put Meningitis Outcome Data
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put Meningitis Outcome data",
     *   input = "NS\SentinelBundle\Form\Meningitis\OutcomeType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/{objId}/outcome",name="nsApiMeningitisPatchOutcome")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function patchMeningitisOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', OutcomeType::class, Meningitis::class);
    }

    /**
     * Put Meningitis Case
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put Meningitis case",
     *   input = "NS\SentinelBundle\Form\Meningitis\CaseType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         400 = "Bad Request",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/{objId}",name="nsApiMeningitisPutCase")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putMeningitisCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', CaseType::class, Meningitis::class);
    }

    /**
     * Put Meningitis Lab Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates lab data for an Meningitis case",
     *  input = "NS\SentinelBundle\Form\Meningitis\SiteLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Put("/{objId}/lab",name="nsApiMeningitisPutLab")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putMeningitisLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', SiteLabType::class, SiteLab::class);
    }

    /**
     * Put Meningitis RRL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates RRL data for an Meningitis case",
     *  input = "NS\SentinelBundle\Form\Meningitis\ReferenceLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Put("/{objId}/rrl",name="nsApiMeningitisPutRRL")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putMeningitisRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', ReferenceLabType::class, ReferenceLab::class);
    }

    /**
     * Put Meningitis NL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates NL data for an Meningitis case",
     *  input = "NS\SentinelBundle\Form\Meningitis\NationalLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Put("/{objId}/nl",name="nsApiMeningitisPutNL")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putMeningitisNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', NationalLabType::class, NationalLab::class);
    }

    /**
     * Put Meningitis Outcome Data
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put Meningitis Outcome data",
     *   input = "NS\SentinelBundle\Form\Meningitis\OutcomeType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/{objId}/outcome",name="nsApiMeningitisPutOutcome")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putMeningitisOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', OutcomeType::class, Meningitis::class);
    }

    /**
     * This method is used to create a new Meningitis case. This must be called prior to setting any data
     * on the case. Although there is a 'type' field, the api should only ever set the field to 1.
     * The case is created, the status code is 204 and the new case is specified in the returned
     * 'Location' header.
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Creates a new Meningitis case",
     *   input = "NS\SentinelBundle\Form\CreateType",
     *   statusCodes = {
     *      201 = "Returned when the case is created"
     *  }
     * )
     *
     * @REST\Post("/",name="nsApiMeningitisPostCase")
     * @REST\View()
     *
     * @param Request $request the request object
     *
     * @return array|View
     */
    public function postMeningitisCaseAction(Request $request)
    {
        return $this->postCase($request, 'nsApiMeningitisGetCase', CreateType::class, Meningitis::class);
    }
}
