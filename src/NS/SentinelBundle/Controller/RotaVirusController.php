<?php

namespace NS\SentinelBundle\Controller;

use \NS\SentinelBundle\Entity\Rota\SiteLab;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
use \NS\SentinelBundle\Form\Types\CreateRoles;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{_locale}/rota")
 */
class RotaVirusController extends Controller
{
    /**
     * @Route("/",name="rotavirusIndex")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $filterForm = $this->createForm('rotavirus_filter_form');
        $filterForm->handleRequest($request);

        if ($filterForm->isValid())
        {
            $query = $this->get('ns.model_manager')
                ->getRepository('NSSentinelBundle:RotaVirus')
                ->getFilterQueryBuilder();

            // build the query from the given form object
            $queryBuilderUpdater = $this->get('lexik_form_filter.query_builder_updater');
            $queryBuilderUpdater->addFilterConditions($filterForm, $query, 'm');
        }
        else
            $query = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:RotaVirus")->getLatestQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->get('page', 1), $request->getSession()->get('result_per_page', 10));
        $createForm = ($this->get('security.context')->isGranted('ROLE_CAN_CREATE')) ? $this->createForm('create_case')->createView() : null;

        return array(
            'pagination' => $pagination,
            'form'       => $this->createForm('results_per_page')->createView(),
            'filterForm' => $filterForm->createView(),
            'createForm' => $createForm);
    }

    /**
     * @param Request $request
     * @Route("/create",name="rotavirusCreate")
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
            $case      = $entityMgr->getRepository('NSSentinelBundle:RotaVirus')->findOrCreate($caseId);

            if (!$case->getId())
            {
                $site = ($form->has('site')) ? $form->get('site')->getData() : $this->get('ns.sentinel.sites')->getSite();
                $case->setSite($site);
            }

            $entityMgr->persist($case);
            $entityMgr->flush();

            return $this->redirect($this->generateUrl($type->getRoute('rotavirus'), array(
                        'id' => $case->getId())));
        }

        return $this->redirect($this->generateUrl('rotavirusIndex'));
    }

    /**
     * @Route("/edit/{id}",name="rotavirusEdit",defaults={"id"=null})
     * @Template()
     */
    public function editAction(Request $request, $id = null)
    {
        return $this->edit($request, 'rotavirus', $id);
    }

    /**
     * @Route("/lab/edit/{id}",name="rotavirusLabEdit",defaults={"id"=null})
     * @Template()
     */
    public function editLabAction(Request $request, $id = null)
    {
        return $this->edit($request, 'rotavirus_lab', $id);
    }

    /**
     * @Route("/rrl/edit/{id}",name="rotavirusRRLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:RotaVirus:editBaseLab.html.twig")
     */
    public function editRRLAction(Request $request, $id = null)
    {
        return $this->edit($request, 'rotavirus_referencelab', $id);
    }

    /**
     * @Route("/nl/edit/{id}",name="rotavirusNLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:RotaVirus:editBaseLab.html.twig")
     */
    public function editNLAction(Request $request, $id = null)
    {
        return $this->edit($request, 'rotavirus_nationallab', $id);
    }

    /**
     * @Route("/outcome/edit/{id}",name="rotavirusOutcomeEdit",defaults={"id"=null})
     * @Template()
     */
    public function editOutcomeAction(Request $request, $id = null)
    {
        return $this->edit($request, 'rotavirus_outcome', $id);
    }

    private function getForm($type, $objId = null)
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
            $entityMgr = $this->getDoctrine()->getManager();
            $record    = $form->getData();
            $entityMgr->persist($record);
            $entityMgr->flush();

            // TODO Flash service required
            return $this->redirect($this->generateUrl("rotavirusIndex"));
        }

        $routeType = ($type == 'rotavirus_referencelab') ? 'RRL' : 'NL';

        return array('form' => $form->createView(), 'id' => $objId, 'type' => $routeType);
    }

    /**
     * @Route("/show/{id}",name="rotavirusShow")
     * @Template()
     */
    public function showAction($id)
    {
        try
        {
            return array('record' => $this->get('ns.model_manager')->getRepository('NSSentinelBundle:RotaVirus')->get($id));
        }
        catch (NonExistentCase $ex)
        {
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig', array(
                    'message' => $ex->getMessage()));
        }
    }
}
