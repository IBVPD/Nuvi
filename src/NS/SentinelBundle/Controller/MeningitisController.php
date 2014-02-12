<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use \NS\SentinelBundle\Form\MeningitisType;
use \NS\SentinelBundle\Form\MeningitisSearch;
use \NS\SentinelBundle\Exceptions\NonExistentCase;

/**
 * @Route("/{_locale}/ibd")
 */
class MeningitisController extends Controller
{
    /**
     * @Route("/",name="meningitisIndex")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $paginator  = $this->get('knp_paginator');

        $filterForm = $this->createForm('meningitis_filter_form');
        $filterForm->submit($request);

        if($filterForm->isValid() && $filterForm->isSubmitted())
        {
            $query = $this->get('ns.model_manager')
                          ->getRepository('NSSentinelBundle:Meningitis')
                          ->getFilterQueryBuilder();

            // build the query from the given form object
            $qb    = $this->get('lexik_form_filter.query_builder_updater');
            $qb->addFilterConditions($filterForm, $query, 'm');
        }
        else
            $query = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:Meningitis")->getLatestQuery();

        $pagination = $paginator->paginate( $query,
                                            $request->query->get('page',1),
                                            $request->getSession()->get('result_per_page',10) );

        $sc = $this->get('security.context');

        if($sc->isGranted('ROLE_SITE'))
            $t = array('header_template'=>'NSSentinelBundle:Meningitis:indexSiteHeader.html.twig', 'row_template'=>'NSSentinelBundle:Meningitis:indexSiteRow.html.twig');
        else if($sc->isGranted('ROLE_LAB'))
            $t = array('header_template'=>'NSSentinelBundle:Meningitis:indexSiteHeader.html.twig', 'row_template'=>'NSSentinelBundle:Meningitis:indexSiteRow.html.twig');
        else if($sc->isGranted('ROLE_RRL_LAB'))
            $t = array('header_template'=>'NSSentinelBundle:Meningitis:indexSiteHeader.html.twig', 'row_template'=>'NSSentinelBundle:Meningitis:indexSiteRow.html.twig');
        else if($sc->isGranted('ROLE_COUNTRY'))
            $t = array('header_template'=>'NSSentinelBundle:Meningitis:indexCountryHeader.html.twig', 'row_template'=>'NSSentinelBundle:Meningitis:indexCountryRow.html.twig');
        else if($sc->isGranted('ROLE_REGION'))
            $t = array('header_template'=>'NSSentinelBundle:Meningitis:indexRegionHeader.html.twig', 'row_template'=>'NSSentinelBundle:Meningitis:indexRegionRow.html.twig');

        return array('pagination' => $pagination, 't' => $t, 'form' => $this->createForm('results_per_page')->createView(),'filterForm'=>$filterForm->createView());
    }

    /**
     * @Route("/create",name="meningitisCreate")
     * @Route("/edit/{id}",name="meningitisEdit",defaults={"id"=null})
     * @Template()
     */
    public function editAction(Request $request,$id = null)
    {
        return $this->edit('meningitis',$request,$id);
    }

    /**
     * @Route("/rrl/create/{id}",name="meningitisRRLCreate")
     * @Route("/rrl/edit/{id}",name="meningitisRRLEdit",defaults={"id"=null})
     * @Template()
     */
    public function editRRLAction(Request $request,$id = null)
    {
        return $this->edit('rrl',$request,$id);
    }

    /**
     * @Route("/lab/create/{id}",name="meningitisLabCreate")
     * @Route("/lab/edit/{id}",name="meningitisLabEdit",defaults={"id"=null})
     * @Template()
     */
    public function editLabAction(Request $request,$id = null)
    {
        return $this->edit('lab',$request,$id);
    }

    private function edit($type, Request $request, $id)
    {
        try 
        {
            switch($type)
            {
                case 'meningitis':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Meningitis')->find($id): null;
                    $form   = $this->createForm('meningitis',$record);
                    break;
                case 'lab':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:SiteLab')->findOrCreateNew($id);
                    $form   = $this->createForm('meningitis_sitelab',$record);
                    break;
                case 'rrl':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:ReferenceLab')->findOrCreateNew($id);
                    $form   = $this->createForm('meningitis_referencelab',$record);
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

                try
                {
                    $em->flush();
                }
                catch(\Exception $e)
                {
                    // TODO Flash service required
                    return array('form' => $form->createView(),'id'=>$id);
                }

                // TODO Flash service required
                return $this->redirect($this->generateUrl("meningitisIndex"));
            }
        }

        return array('form' => $form->createView(),'id'=>$id);
    }

    /**
     * @Route("/show/{id}",name="meningitisShow")
     * @Template()
     */
    public function showAction($id)
    {
        return array('record' => $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Meningitis')->get($id));
    }
}
