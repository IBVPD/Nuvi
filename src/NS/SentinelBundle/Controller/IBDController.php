<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use NS\SentinelBundle\Exceptions\NonExistentCase;
use NS\SentinelBundle\Form\Types\CreateRoles;

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
        $paginator  = $this->get('knp_paginator');

        $filterForm = $this->createForm('ibd_filter_form');
        $filterForm->submit($request);

        if($filterForm->isValid() && $filterForm->isSubmitted())
        {
            $query = $this->get('ns.model_manager')
                          ->getRepository('NSSentinelBundle:IBD')
                          ->getFilterQueryBuilder();

            // build the query from the given form object
            $qb    = $this->get('lexik_form_filter.query_builder_updater');
            $qb->addFilterConditions($filterForm, $query);
        }
        else
            $query = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:IBD")->getLatestQuery();

        $pagination = $paginator->paginate( $query,
                                            $request->query->get('page',1),
                                            $request->getSession()->get('result_per_page',10) );

        $sc = $this->get('security.context');

        if($sc->isGranted('ROLE_SITE') || $sc->isGranted('ROLE_LAB'))
            $t = array('header_template'=>'NSSentinelBundle:IBD:indexSiteHeader.html.twig', 'row_template'=>'NSSentinelBundle:IBD:indexSiteRow.html.twig');
        else if($sc->isGranted('ROLE_COUNTRY'))
            $t = array('header_template'=>'NSSentinelBundle:IBD:indexCountryHeader.html.twig', 'row_template'=>'NSSentinelBundle:IBD:indexCountryRow.html.twig');
        else if($sc->isGranted('ROLE_REGION'))
            $t = array('header_template'=>'NSSentinelBundle:IBD:indexRegionHeader.html.twig', 'row_template'=>'NSSentinelBundle:IBD:indexRegionRow.html.twig');

        $createForm = ($sc->isGranted('ROLE_CAN_CREATE')) ? $this->createForm('create_ibd')->createView():null;

        return array(
                    'pagination' => $pagination,
                    't'          => $t,
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
        $form = $this->createForm('create_ibd');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $caseId = $form->get('caseId')->getData();
            $type   = $form->get('type')->getData();
            $em     = $this->get('ns.model_manager');
            $case   = $em->getRepository('NSSentinelBundle:IBD')->findOrCreate($caseId,null);

            if(!$case->getId())
            {
                if($form->has('site'))
                    $site = $form->get('site')->getData();
                else
                    $site = $this->get('ns.sentinel.sites')->getSite();

                $case->setSite($site);
            }

            switch($type->getValue())
            {
                case CreateRoles::BASE:
                    $res = 'ibdEdit';
                    break;
                case CreateRoles::LAB:
                    $res = 'ibdLabEdit';
                    break;
                default:
                    $res = 'ibdIndex';
                    break;
            }

            $em->persist($case);
            $em->flush();

            return $this->redirect($this->generateUrl($res,array('id' => $case->getId())));
        }

        return $this->redirect($this->generateUrl('ibdIndex'));
    }

    /**
     * @Route("/edit/{id}",name="ibdEdit",defaults={"id"=null})
     * @Template()
     */
    public function editAction(Request $request,$id = null)
    {
        return $this->edit($request,'ibd',$id);
    }

    /**
     * @Route("/lab/edit/{id}",name="ibdLabEdit",defaults={"id"=null})
     * @Template()
     */
    public function editLabAction(Request $request,$id = null)
    {
        return $this->edit($request, 'lab', $id);
    }

    /**
     * @Route("/outcome/edit/{id}",name="ibdOutcomeEdit",defaults={"id"=null})
     * @Template()
     */
    public function editOutcomeAction(Request $request,$id = null)
    {
        return $this->edit($request, 'outcome', $id);
    }

    private function edit(Request $request, $type, $id = null)
    {
        try 
        {
            switch($type)
            {
                case 'ibd':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD')->find($id): null;
                    $form   = $this->createForm('ibd',$record);
                    break;
                case 'outcome':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD')->find($id): null;
                    $form   = $this->createForm('ibd_outcome',$record);
                    break;
                case 'lab':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD\Lab')->findOrCreateNew($id): null;
                    $form   = $this->createForm('ibd_lab',$record);
                    break;
                default:
                    throw new \Exception("Unknown type");
            }
        }
        catch (NonExistentCase $ex) 
        {
            // TODO Flash service required
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig',array('message' => $ex->getMessage()));
        }

        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $em     = $this->getDoctrine()->getManager();
                $record = $form->getData();

                $em->persist($record);

                if($type == 'lab')
                    $em->persist($record->getCase());

                try
                {
                    $em->flush();
                }
                catch(\Doctrine\DBAL\DBALException $e)
                {
                    // TODO Flash service required
                    if($e->getPrevious()->getCode() === '23000')
                        $form->addError(new FormError ("The case id already exists for this site!"));
                    else
                        die("ERROR: ".$e->getMessage());

                    return array('form' => $form->createView(),'id'=>$id, 'type'=>strtoupper($type));
                }

                // TODO Flash service required
                return $this->redirect($this->generateUrl("ibdIndex"));
            }
        }

        return array('form' => $form->createView(),'id'=>$id, 'type'=>strtoupper($type));
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
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig',array('message' => $ex->getMessage()));
        }
    }
}