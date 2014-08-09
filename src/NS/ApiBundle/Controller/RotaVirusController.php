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
     * @REST\Get("/case/{id}")
     *
     * @param string  $id      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
    */
    public function getRotaCaseAction($id)
    {
        return $this->getCase('rota',$id);
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
     * @REST\Get("/case/{id}/lab")
     *
     * @param string  $id      the object id
     *
     * @return array
     *
     * @throws NotFoundHttpException when case not exist
     * @throws NonExistentCase when case doees not exist
    */
    public function getRotaCaseLabAction($id)
    {
        return $this->getCaseLab('rota',$id);
    }

    /**
     * Patch RotaVirus Case
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Patch RotaVirus case",
     *   input = "rotavirus",
     *   statusCodes = {
     *         202 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/case/{id}")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     *
     */
    public function patchRotaCasesAction(Request $request, $id)
    {
        $route = $this->generateUrl('ns_api_rotavirus_getrotacase', array('id' => $id));

        return $this->updateCase($request, $id, 'PATCH', 'rotavirus', 'NSSentinelBundle:RotaVirus', $route);
    }

    /**
     * Patch RotaVirus Lab Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus Lab data",
     *  input = "rotavirus_lab",
     *  statusCodes={
     *         202 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     * @REST\Patch("/case/{id}/lab")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     */
    public function patchRotaLabAction(Request $request, $id)
    {
        $route = $this->generateUrl('ns_api_rotavirus_getrotacaselab', array('id' => $id));

        return $this->updateLab($request, $id, 'PATCH', 'rotavirus_lab', 'NSSentinelBundle:Rota\Lab', $route);
    }

    /**
     * Patch RotaVirus Outcome Data
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Patch RotaVirus Outcome data",
     *   input = "rotavirus_outcome",
     *   statusCodes = {
     *         202 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Patch("/case/{id}/outcome")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     *
     */
    public function patchRotaOutcomeAction(Request $request, $id)
    {
        $route = $this->generateUrl('ns_api_rotavirus_getrotacase', array('id' => $id));

        return $this->updateCase($request, $id, 'PATCH', 'rotavirus_outcome', 'NSSentinelBundle:RotaVirus', $route);
    }

    /**
     * Put RotaVirus Case
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Put RotaVirus case",
     *   input = "rotavirus",
     *   statusCodes = {
     *         202 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/case/{id}")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     *
     */
    public function putRotaCasesAction(Request $request, $id)
    {
        $route = $this->generateUrl('ns_api_rotavirus_getrotacase', array('id' => $id));

        return $this->updateCase($request, $id, 'PUT', 'rotavirus', 'NSSentinelBundle:RotaVirus', $route);
    }

    /**
     * Put RotaVirus Lab Data,
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Updates a RotaVirus Lab data",
     *  input = "rotavirus_lab",
     *  statusCodes={
     *         202 = "Returned when successful",
     *         406 = "Returned when there is an issue with the form data"
     *         }
     * )
     *
     * @REST\Put("/case/{id}/lab")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     */
    public function putRotaLabAction(Request $request, $id)
    {
        $route = $this->generateUrl('ns_api_rotavirus_getrotacaselab', array('id' => $id));

        return $this->updateLab($request, $id, 'PUT', 'rotavirus_lab', 'NSSentinelBundle:Rota\Lab', $route);
    }

    /**
     * Put RotaVirus Outcome Data
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Put RotaVirus Outcome data",
     *   input = "rotavirus_outcome",
     *   statusCodes = {
     *         202 = "Returned when successful",
     *         406 = "Returned when there are validation issues with the case",
     *          }
     * )
     *
     * @REST\Put("/case/{id}/outcome")
     * @REST\View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $id
     *
     */
    public function putRotaOutcomeAction(Request $request, $id)
    {
        $route = $this->generateUrl('ns_api_rotavirus_getrotacase', array('id' => $id));

        return $this->updateCase($request, $id, 'PUT', 'rotavirus_outcome', 'NSSentinelBundle:RotaVirus', $route);
    }

    /**
     * Create a RotaVirus Case,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Rotavirus case",
     *   input = "create_rotavirus"
     * )
     *
     * @REST\Post("/case")
     * @REST\View()
     *
     * @param Request $request the request object
    */
    public function postRotaCasesAction(Request $request)
    {
        return $this->postCase($request,'ns_api_rotavirus_getrotacase','create_rotavirus','NSSentinelBundle:RotaVirus');
    }
}
