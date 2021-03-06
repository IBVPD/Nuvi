<?php

namespace NS\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Entity\RotaVirus\NationalLab;
use NS\SentinelBundle\Entity\RotaVirus\ReferenceLab;
use NS\SentinelBundle\Entity\RotaVirus\SiteLab;
use NS\SentinelBundle\Exceptions\NonExistentCaseException;
use NS\SentinelBundle\Form\CreateType;
use NS\SentinelBundle\Form\RotaVirus\CaseType;
use NS\SentinelBundle\Form\RotaVirus\NationalLabType;
use NS\SentinelBundle\Form\RotaVirus\OutcomeType;
use NS\SentinelBundle\Form\RotaVirus\ReferenceLabType;
use NS\SentinelBundle\Form\RotaVirus\SiteLabType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        return $this->getCase(RotaVirus::class, $objId);
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
     * @throws NonExistentCaseException when case does not exist
    */
    public function getRotaCaseLabAction($objId)
    {
        return $this->getLab(SiteLab::class, $objId);
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
     * @throws NonExistentCaseException when case does not exist
     */
    public function getRotaCaseRRLAction($objId)
    {
        return $this->getLab(ReferenceLab::class, $objId);
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
     * @throws NonExistentCaseException when case does not exist
     */
    public function getRotaCaseNLAction($objId)
    {
        return $this->getLab(NationalLab::class, $objId);
    }

    /**
     * Patch RotaVirus Case
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Patch RotaVirus case",
     *   input = "NS\SentinelBundle\Form\RotaVirus\CaseType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/{objId}",name="nsApiRotaPatchCase")
     * @REST\View()
     *
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchRotaCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', CaseType::class, RotaVirus::class);
    }

    /**
     * Patch RotaVirus Lab Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus Lab data",
     *  input = "NS\SentinelBundle\Form\RotaVirus\SiteLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/lab",name="nsApiRotaPatchLab")
     * @REST\View()
     *
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchRotaLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', SiteLabType::class, SiteLab::class);
    }

    /**
     * Patch RotaVirus RRL Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus RRL data",
     *  input = "NS\SentinelBundle\Form\RotaVirus\ReferenceLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/rrl",name="nsApiRotaPatchRRL")
     * @REST\View()
     *
     * @param Request $request
     * @param string  $objId
     *
     * @return View
     */
    public function patchRotaRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', ReferenceLabType::class, ReferenceLab::class);
    }

    /**
     * Patch RotaVirus NL Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus NL data",
     *  input = "NS\SentinelBundle\Form\RotaVirus\NationalLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/{objId}/nl",name="nsApiRotaPatchNL")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function patchRotaNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PATCH', NationalLabType::class, NationalLab::class);
    }

    /**
     * Patch RotaVirus Outcome Data
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Patch RotaVirus Outcome data",
     *   input = "NS\SentinelBundle\Form\RotaVirus\OutcomeType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/{objId}/outcome",name="nsApiRotaPatchOutcome")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function patchRotaOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PATCH', OutcomeType::class, RotaVirus::class);
    }

    /**
     * Put RotaVirus Case
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Put RotaVirus case",
     *   input = "NS\SentinelBundle\Form\RotaVirus\CaseType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/{objId}",name="nsApiRotaPutCase")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putRotaCaseAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', CaseType::class, RotaVirus::class);
    }

    /**
     * Put RotaVirus Lab Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus Lab data",
     *  input = "NS\SentinelBundle\Form\RotaVirus\SiteLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     *
     * @REST\Put("/{objId}/lab",name="nsApiRotaPutLab")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putRotaLabAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', SiteLabType::class, SiteLab::class);
    }

    /**
     * Put RotaVirus RRL Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus RRL data",
     *  input = "NS\SentinelBundle\Form\RotaVirus\ReferenceLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     *
     * @REST\Put("/{objId}/rrl",name="nsApiRotaPutRRL")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putRotaRRLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', ReferenceLabType::class, ReferenceLab::class);
    }

    /**
     * Put RotaVirus NL Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus NL data",
     *  input = "NS\SentinelBundle\Form\RotaVirus\NationalLabType",
     *  statusCodes={
     *         204 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     *
     * @REST\Put("/{objId}/nl",name="nsApiRotaPutNL")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putRotaNLAction(Request $request, $objId)
    {
        return $this->updateLab($request, $objId, 'PUT', NationalLabType::class, NationalLab::class);
    }

    /**
     * Put RotaVirus Outcome Data
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Put RotaVirus Outcome data",
     *   input = "NS\SentinelBundle\Form\RotaVirus\OutcomeType",
     *   statusCodes = {
     *         204 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/{objId}/outcome",name="nsApiRotaPutOutcome")
     * @REST\View()
     *
     * @param Request $request
     * @param string $objId
     *
     * @return View
     */
    public function putRotaOutcomeAction(Request $request, $objId)
    {
        return $this->updateCase($request, $objId, 'PUT', OutcomeType::class, RotaVirus::class);
    }

    /**
     * Create a RotaVirus Case,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Rotavirus case",
     *   input = "NS\SentinelBundle\Form\CreateType"
     * )
     *
     * @REST\Post("/",name="nsApiRotaPostCase")
     * @REST\View()
     *
     * @param Request $request the request object
     *
     * @return array|View
     */
    public function postRotaCaseAction(Request $request)
    {
        return $this->postCase($request, 'nsApiRotaGetCase', CreateType::class, RotaVirus::class);
    }
}
