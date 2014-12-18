<?php

namespace NS\SentinelBundle\Controller;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{_locale}/rota")
 */
class RotaVirusController extends BaseCaseController
{
    /**
     * @Route("/",name="rotavirusIndex")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return $this->index($request, 'NSSentinelBundle:RotaVirus', 'rotavirus_filter_form');
    }

    /**
     * @param Request $request
     * @Route("/create",name="rotavirusCreate")
     * @Template()
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        return $this->create($request, 'NSSentinelBundle:RotaVirus', 'rotavirusIndex', 'rotavirus');
    }

    /**
     * @Route("/edit/{id}",name="rotavirusEdit",defaults={"id"=null})
     * @Template()
     */
    public function editAction(Request $request, $id = null)
    {
        return $this->edit($request, 'rotavirus', "rotavirusIndex", "rotavirusEdit", $id);
    }

    /**
     * @Route("/lab/edit/{id}",name="rotavirusLabEdit",defaults={"id"=null})
     * @Template()
     */
    public function editLabAction(Request $request, $id = null)
    {
        return $this->edit($request, 'rotavirus_lab', "rotavirusIndex", "rotavirusLabEdit", $id);
    }

    /**
     * @Route("/rrl/edit/{id}",name="rotavirusRRLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:RotaVirus:editBaseLab.html.twig")
     */
    public function editRRLAction(Request $request, $id = null)
    {
        return $this->edit($request, 'rotavirus_referencelab', "rotavirusIndex", "rotavirusRRLEdit", $id);
    }

    /**
     * @Route("/nl/edit/{id}",name="rotavirusNLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:RotaVirus:editBaseLab.html.twig")
     */
    public function editNLAction(Request $request, $id = null)
    {
        return $this->edit($request, 'rotavirus_nationallab', "rotavirusIndex", "rotavirusNLEdit", $id);
    }

    /**
     * @Route("/outcome/edit/{id}",name="rotavirusOutcomeEdit",defaults={"id"=null})
     * @Template()
     */
    public function editOutcomeAction(Request $request, $id = null)
    {
        return $this->edit($request, 'rotavirus_outcome', "rotavirusIndex", "rotavirusOutcomeEdit", $id);
    }

    public function getForm($type, $objId = null)
    {
        $record = null;
        if ($objId)
        {
            switch ($type)
            {
                case 'rotavirus':
                case 'rotavirus_outcome':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:RotaVirus')->find($objId);
                    break;

                case 'rotavirus_lab':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Rota\SiteLab')->findOrCreateNew($objId);
                    break;

                case 'rotavirus_referencelab':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Rota\ReferenceLab')->findOrCreateNew($objId);
                    break;

                case 'rotavirus_nationallab':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Rota\NationalLab')->findOrCreateNew($objId);
                    break;

                default:
                    throw new \Exception("Unknown type");
            }
        }

        return $this->createForm($type, $record);
    }

    /**
     * @Route("/show/{id}",name="rotavirusShow")
     * @Template()
     */
    public function showAction($id)
    {
        return $this->show('NSSentinelBundle:RotaVirus', $id);
    }
}
