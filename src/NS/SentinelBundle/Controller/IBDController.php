<?php

namespace NS\SentinelBundle\Controller;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Symfony\Component\HttpFoundation\Request;
use \NS\SentinelBundle\Exceptions\NonExistentCase;

/**
 * @Route("/{_locale}/ibd")
 */
class IBDController extends BaseCaseController
{
    /**
     * @Route("/",name="ibdIndex")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return $this->index($request, 'NSSentinelBundle:IBD', 'ibd_filter_form');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/create",name="ibdCreate")
     * @Template()
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        return $this->create($request, 'NSSentinelBundle:IBD', 'ibdIndex', 'ibd');
    }

    /**
     * @Route("/edit/{id}",name="ibdEdit",defaults={"id"=null})
     * @Template()
     */
    public function editAction(Request $request, $id = null)
    {
        return $this->edit($request, 'ibd', "ibdIndex", "ibdEdit", $id);
    }

    /**
     * @Route("/rrl/edit/{id}",name="ibdRRLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:IBD:editBaseLab.html.twig")
     */
    public function editRRLAction(Request $request, $id = null)
    {
        return $this->edit($request, 'ibd_referencelab', "ibdIndex", "ibdRRLEdit", $id);
    }

    /**
     * @Route("/nl/edit/{id}",name="ibdNLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:IBD:editBaseLab.html.twig")
     */
    public function editNLAction(Request $request, $id = null)
    {
        return $this->edit($request, 'ibd_nationallab', "ibdIndex", "ibdNLEdit", $id);
    }

    /**
     * @Route("/lab/edit/{id}",name="ibdLabEdit",defaults={"id"=null})
     * @Template()
     */
    public function editLabAction(Request $request, $id = null)
    {
        return $this->edit($request, 'ibd_lab', "ibdIndex", "ibdLabEdit", $id);
    }

    /**
     * @Route("/outcome/edit/{id}",name="ibdOutcomeEdit",defaults={"id"=null})
     * @Template()
     */
    public function editOutcomeAction(Request $request, $id = null)
    {
        return $this->edit($request, 'ibd_outcome', "ibdIndex", "ibdOutcomeEdit", $id);
    }

    public function getForm($type, $objId = null)
    {
        $record = null;

        if ($objId)
        {
            switch ($type)
            {
                case 'ibd':
                case 'ibd_outcome':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD')->find($objId);
                    break;
                case 'ibd_lab':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD\SiteLab')->findOrCreateNew($objId);
                    break;
                case 'ibd_referencelab':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD\ReferenceLab')->findOrCreateNew($objId);
                    break;
                case 'ibd_nationallab':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD\NationalLab')->findOrCreateNew($objId);
                    break;
                default:
                    throw new \RuntimeException("Unknown type");
            }
        }

        return $this->createForm($type, $record);
    }

    /**
     * @Route("/show/{id}",name="ibdShow")
     * @Template()
     */
    public function showAction($id)
    {
        return $this->show('NSSentinelBundle:IBD', $id);
    }

}
