<?php

namespace NS\SentinelBundle\Controller;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{_locale}/ibd")
 */
class IBDController extends BaseCaseController
{
    /**
     * @Route("/",name="ibdIndex")
     * @Method(methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        return $this->render('NSSentinelBundle:IBD:index.html.twig',$this->index($request, 'NSSentinelBundle:IBD', 'ibd_filter_form'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/create",name="ibdCreate")
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        return $this->create($request, 'NSSentinelBundle:IBD', 'ibdIndex', 'ibd');
    }

    /**
     * @Route("/edit/{id}",name="ibdEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     */
    public function editAction(Request $request, $id = null)
    {
        return $this->render('NSSentinelBundle:IBD:edit.html.twig',$this->edit($request, 'ibd', "ibdIndex", "ibdEdit", $id));
    }

    /**
     * @Route("/rrl/edit/{id}",name="ibdRRLEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     */
    public function editRRLAction(Request $request, $id = null)
    {
        return $this->render('NSSentinelBundle:IBD:editBaseLab.html.twig',$this->edit($request, 'ibd_referencelab', "ibdIndex", "ibdRRLEdit", $id));
    }

    /**
     * @Route("/nl/edit/{id}",name="ibdNLEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     */
    public function editNLAction(Request $request, $id = null)
    {
        return $this->render('NSSentinelBundle:IBD:editBaseLab.html.twig',$this->edit($request, 'ibd_nationallab', "ibdIndex", "ibdNLEdit", $id));
    }

    /**
     * @Route("/lab/edit/{id}",name="ibdLabEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     */
    public function editLabAction(Request $request, $id = null)
    {
        return $this->render('NSSentinelBundle:IBD:editLab.html.twig',$this->edit($request, 'ibd_lab', "ibdIndex", "ibdLabEdit", $id));
    }

    /**
     * @Route("/outcome/edit/{id}",name="ibdOutcomeEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     */
    public function editOutcomeAction(Request $request, $id = null)
    {
        return $this->render('NSSentinelBundle:IBD:editOutcome.html.twig',$this->edit($request, 'ibd_outcome', "ibdIndex", "ibdOutcomeEdit", $id));
    }

    /**
     * @param $type
     * @param null $objId
     * @return \Symfony\Component\Form\Form
     * @throws \Doctrine\ORM\UnexpectedResultException
     * @throws \Exception
     */
    protected function getForm($type, $objId = null)
    {
        $record = null;

        if ($objId)
        {
            switch ($type)
            {
                case 'ibd':
                case 'ibd_outcome':
                    $record = $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD')->find($objId);
                    break;
                case 'ibd_lab':
                    $record = $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD\SiteLab')->findOrCreateNew($objId);
                    break;
                case 'ibd_referencelab':
                    $record = $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD\ReferenceLab')->findOrCreateNew($objId);
                    break;
                case 'ibd_nationallab':
                    $record = $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD\NationalLab')->findOrCreateNew($objId);
                    break;
                default:
                    throw new \RuntimeException("Unknown type");
            }
        }

        return $this->createForm($type, $record);
    }

    /**
     * @Route("/show/{id}",name="ibdShow")
     * @Method(methods={"GET"})
     */
    public function showAction($id)
    {
        return $this->render('NSSentinelBundle:IBD:show.html.twig',$this->show('NSSentinelBundle:IBD', $id));
    }
}
