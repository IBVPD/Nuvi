<?php

namespace NS\SentinelBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Symfony\Component\HttpFoundation\Request;
use \NS\SentinelBundle\Exceptions\NonExistentCase;

/**
 * @Route("/{_locale}/ibd")
 */
class IBDController extends Controller
{
    /**
     * @Route("/",name="ibdIndex")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $filterForm = $this->createForm('ibd_filter_form');
        $filterForm->handleRequest($request);

        if ($filterForm->isValid())
        {
            $query = $this->get('ns.model_manager')
                ->getRepository('NSSentinelBundle:IBD')
                ->getFilterQueryBuilder();

            // build the query from the given form object
            $queryBuilderUpdater = $this->get('lexik_form_filter.query_builder_updater');
            $queryBuilderUpdater->addFilterConditions($filterForm, $query, 'm');
        }
        else
            $query = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:IBD")->getLatestQuery();

        $paginator       = $this->get('knp_paginator');
        $pagination      = $paginator->paginate($query, $request->query->get('page', 1), $request->getSession()->get('result_per_page', 10));
        $createForm = ($this->get('security.context')->isGranted('ROLE_CAN_CREATE')) ? $this->createForm('create_case')->createView() : null;

        return array(
            'pagination' => $pagination,
            'form'       => $this->createForm('results_per_page')->createView(),
            'filterForm' => $filterForm->createView(),
            'createForm' => $createForm);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/create",name="ibdCreate")
     * @Template()
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm('create_case');
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $caseId    = $form->get('caseId')->getData();
            $type      = $form->get('type')->getData();
            $entityMgr = $this->get('ns.model_manager');
            $case      = $entityMgr->getRepository('NSSentinelBundle:IBD')->findOrCreate($caseId, null);

            if (!$case->getId())
            {
                $site = ($form->has('site')) ? $form->get('site')->getData() : $this->get('ns.sentinel.sites')->getSite();
                $case->setSite($site);
            }

            $entityMgr->persist($case);
            $entityMgr->flush();

            return $this->redirect($this->generateUrl($type->getRoute('ibd'), array(
                        'id' => $case->getId())));
        }

        return $this->redirect($this->generateUrl('ibdIndex'));
    }

    /**
     * @Route("/edit/{id}",name="ibdEdit",defaults={"id"=null})
     * @Template()
     */
    public function editAction(Request $request, $id = null)
    {
        return $this->edit($request, 'ibd', $id);
    }

    /**
     * @Route("/rrl/edit/{id}",name="ibdRRLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:IBD:editBaseLab.html.twig")
     */
    public function editRRLAction(Request $request, $id = null)
    {
        return $this->edit($request, 'ibd_referencelab', $id);
    }

    /**
     * @Route("/nl/edit/{id}",name="ibdNLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:IBD:editBaseLab.html.twig")
     */
    public function editNLAction(Request $request, $id = null)
    {
        return $this->edit($request, 'ibd_nationallab', $id);
    }

    /**
     * @Route("/lab/edit/{id}",name="ibdLabEdit",defaults={"id"=null})
     * @Template()
     */
    public function editLabAction(Request $request, $id = null)
    {
        return $this->edit($request, 'ibd_lab', $id);
    }

    /**
     * @Route("/outcome/edit/{id}",name="ibdOutcomeEdit",defaults={"id"=null})
     * @Template()
     */
    public function editOutcomeAction(Request $request, $id = null)
    {
        return $this->edit($request, 'ibd_outcome', $id);
    }

    private function getForm($type, $objId = null)
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

    private function edit(Request $request, $type, $objId = null)
    {
        try
        {
            $form = $this->getForm($type, $objId);
        }
        catch (NonExistentCase $ex)
        {
            // TODO Flash service required
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig', array(
                    'message' => $ex->getMessage()));
        }

        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityMgr = $this->get('doctrine.orm.entity_manager');
            $entityMgr->persist($form->getData());
            $entityMgr->flush();

            // TODO Flash service required
            return $this->redirect($this->generateUrl("ibdIndex"));
        }

        $routeType = ($type == 'ibd_referencelab') ? 'RRL' : 'NL';

        return array('form' => $form->createView(), 'id' => $objId, 'type' => $routeType);
    }

    /**
     * @Route("/show/{id}",name="ibdShow")
     * @Template()
     */
    public function showAction($id)
    {
        try
        {
            return array('record' => $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD')->get($id));
        }
        catch (NonExistentCase $ex)
        {
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig', array(
                    'message' => $ex->getMessage()));
        }
    }

}
