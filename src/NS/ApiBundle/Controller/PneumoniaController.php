<?php

namespace NS\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as ApiDoc;
use NS\SentinelBundle\Entity\Pneumonia\NationalLab;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\Pneumonia\ReferenceLab;
use NS\SentinelBundle\Entity\Pneumonia\SiteLab;
use NS\SentinelBundle\Exceptions\NonExistentCaseException;
use NS\SentinelBundle\Form\CreateType;
use NS\SentinelBundle\Form\Pneumonia\CaseType;
use NS\SentinelBundle\Form\Pneumonia\NationalLabType;
use NS\SentinelBundle\Form\Pneumonia\OutcomeType;
use NS\SentinelBundle\Form\Pneumonia\ReferenceLabType;
use NS\SentinelBundle\Form\Pneumonia\SiteLabType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Description of PneumoniaController
 *
 * @author gnat
 * @Route("/api/pneumonia")
 */
class PneumoniaController extends CaseController
{
    /**
     * Retrieves an Pneumonia case by id. Most fields are returned, however some fields
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
     * @REST\Get("/{objId}",name="nsApiPneumoniaGetCase")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case does not exist
     */
    public function getPneumoniaCaseAction($objId)
    {
        return $this->getCase(Pneumonia::class, $objId);
    }

    /**
     * Retrieves an Pneumonia case lab by id. Most fields are returned, however some fields
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
     * @REST\Get("/{objId}/lab",name="nsApiPneumoniaGetLab")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case does not exist
     */
    public function getPneumoniaCaseLabAction($objId)
    {
        return $this->getLab(SiteLab::class, $objId);
    }

    /**
     * Retrieves an Pneumonia case RRL lab by id. Most fields are returned, however some 
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
     * @REST\Get("/{objId}/rrl",name="nsApiPneumoniaGetRRL")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case does not exist
     */
    public function getPneumoniaCaseRRLAction($objId)
    {
        return $this->getLab(ReferenceLab::class, $objId);
    }

    /**
     * Retrieves an Pneumonia case NL lab by id. Most fields are returned, however some
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
     * @REST\Get("/{objId}/nl",name="nsApiPneumoniaGetNL")
     *
     * @param string  $objId      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCaseException when case does not exist
     */
    public function getPneumoniaCaseNLAction($objId)
    {
        return $this->getLab(NationalLab::class, $objId);
    }

    /**
     * Patch Pneumonia Case
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Patch Pneumonia case",
     *   input = "NS\SentinelBundle\Form\Pneumonia\CaseType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         400 = "Bad Request",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/{objId}",name="nsApiPneumoniaPatchCase")
     * @REST\View()
     *
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchPneumoniaCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', CaseType::class, Pneumonia::class);
    }

    /**
     * Patch Pneumonia Lab Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates lab data for an Pneumonia case",
     *  input = "NS\SentinelBundle\Form\Pneumonia\SiteLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/lab",name="nsApiPneumoniaPatchLab")
     * @REST\View()
     *
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchPneumoniaLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', SiteLabType::class, SiteLab::class);
    }

    /**
     * Patch Pneumonia RRL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates Reference Lab data for an Pneumonia case",
     *  input = "NS\SentinelBundle\Form\Pneumonia\ReferenceLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/rrl",name="nsApiPneumoniaPatchRRL")
     * @REST\View()
     *
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchPneumoniaRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', ReferenceLabType::class, ReferenceLab::class);
    }

    /**
     * Patch Pneumonia NL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates National Lab data for an Pneumonia case",
     *  input = "NS\SentinelBundle\Form\Pneumonia\NationalLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/nl",name="nsApiPneumoniaPatchNL")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function patchPneumoniaNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', NationalLabType::class, NationalLab::class);
    }

    /**
     * Put Pneumonia Outcome Data
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put Pneumonia Outcome data",
     *   input = "NS\SentinelBundle\Form\Pneumonia\OutcomeType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/{objId}/outcome",name="nsApiPneumoniaPatchOutcome")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function patchPneumoniaOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', OutcomeType::class, Pneumonia::class);
    }

    /**
     * Put Pneumonia Case
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put Pneumonia case",
     *   input = "NS\SentinelBundle\Form\Pneumonia\CaseType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         400 = "Bad Request",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/{objId}",name="nsApiPneumoniaPutCase")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putPneumoniaCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', CaseType::class, Pneumonia::class);
    }

    /**
     * Put Pneumonia Lab Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates lab data for an Pneumonia case",
     *  input = "NS\SentinelBundle\Form\Pneumonia\SiteLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Put("/{objId}/lab",name="nsApiPneumoniaPutLab")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putPneumoniaLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', SiteLabType::class, SiteLab::class);
    }

    /**
     * Put Pneumonia RRL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates RRL data for an Pneumonia case",
     *  input = "NS\SentinelBundle\Form\Pneumonia\ReferenceLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Put("/{objId}/rrl",name="nsApiPneumoniaPutRRL")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putPneumoniaRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', ReferenceLabType::class, ReferenceLab::class);
    }

    /**
     * Put Pneumonia NL Data,
     *
     * @ApiDoc\ApiDoc(
     *  resource = true,
     *  description = "Updates NL data for an Pneumonia case",
     *  input = "NS\SentinelBundle\Form\Pneumonia\NationalLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Put("/{objId}/nl",name="nsApiPneumoniaPutNL")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putPneumoniaNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', NationalLabType::class, NationalLab::class);
    }

    /**
     * Put Pneumonia Outcome Data
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Put Pneumonia Outcome data",
     *   input = "NS\SentinelBundle\Form\Pneumonia\OutcomeType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/{objId}/outcome",name="nsApiPneumoniaPutOutcome")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putPneumoniaOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', OutcomeType::class, Pneumonia::class);
    }

    /**
     * This method is used to create a new Pneumonia case. This must be called prior to setting any data
     * on the case. Although there is a 'type' field, the api should only ever set the field to 1.
     * The case is created, the status code is 204 and the new case is specified in the returned
     * 'Location' header.
     *
     * @ApiDoc\ApiDoc(
     *   resource = true,
     *   description = "Creates a new Pneumonia case",
     *   input = "NS\SentinelBundle\Form\CreateType",
     *   statusCodes = {
     *      201 = "Returned when the case is created"
     *  }
     * )
     *
     * @REST\Post("/",name="nsApiPneumoniaPostCase")
     * @REST\View()
     *
     * @param Request $request the request object
     *
     * @return array|View
     */
    public function postPneumoniaCaseAction(Request $request)
    {
        return $this->postCase($request, 'nsApiPneumoniaGetCase', CreateType::class, Pneumonia::class);
    }
}
