<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use \NS\SentinelBundle\Exceptions\NonExistentCase;

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
        $paginator  = $this->get('knp_paginator');

        $filterForm = $this->createForm('rotavirus_filter_form');
        $filterForm->submit($request);

        if($filterForm->isValid() && $filterForm->isSubmitted())
        {
            $query = $this->get('ns.model_manager')
                          ->getRepository('NSSentinelBundle:RotaVirus')
                          ->getFilterQueryBuilder();

            // build the query from the given form object
            $qb    = $this->get('lexik_form_filter.query_builder_updater');
            $qb->addFilterConditions($filterForm, $query, 'm');
        }
        else
            $query = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:RotaVirus")->getLatestQuery();

        $pagination = $paginator->paginate( $query,
                                            $request->query->get('page',1),
                                            $request->getSession()->get('result_per_page',10) );

        $sc = $this->get('security.context');

        if($sc->isGranted('ROLE_SITE') || $sc->isGranted('ROLE_LAB') ||$sc->isGranted('ROLE_RRL_LAB'))
            $t = array('header_template'=>'NSSentinelBundle:RotaVirus:indexSiteHeader.html.twig', 'row_template'=>'NSSentinelBundle:RotaVirus:indexSiteRow.html.twig');
        else if($sc->isGranted('ROLE_COUNTRY'))
            $t = array('header_template'=>'NSSentinelBundle:RotaVirus:indexCountryHeader.html.twig', 'row_template'=>'NSSentinelBundle:RotaVirus:indexCountryRow.html.twig');
        else if($sc->isGranted('ROLE_REGION'))
            $t = array('header_template'=>'NSSentinelBundle:RotaVirus:indexRegionHeader.html.twig', 'row_template'=>'NSSentinelBundle:RotaVirus:indexRegionRow.html.twig');

        return array('pagination' => $pagination, 't' => $t, 'form' => $this->createForm('results_per_page')->createView(),'filterForm'=>$filterForm->createView());
    }

    /**
     * @Route("/create",name="rotavirusCreate")
     * @Route("/edit/{id}",name="rotavirusEdit",defaults={"id"=null})
     * @Template()
     */
    public function editAction(Request $request,$id = null)
    {
        return $this->edit($request,'rotavirus',$id);
    }

    /**
     * @Route("/lab/edit/{id}",name="rotavirusLabEdit",defaults={"id"=null})
     * @Template()
     */
    public function editLabAction(Request $request,$id = null)
    {
        return $this->edit($request,'lab',$id);
    }

    /**
     * @Route("/rrl/edit/{id}",name="rotavirusRRLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:RotaVirus:editBaseLab.html.twig")
     */
    public function editRRLAction(Request $request,$id = null)
    {
        return $this->edit($request, 'rrl', $id);
    }

    /**
     * @Route("/nl/edit/{id}",name="rotavirusNLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:RotaVirus:editBaseLab.html.twig")
     */
    public function editNLAction(Request $request,$id = null)
    {
        return $this->edit($request, 'nl', $id);
    }

    private function edit(Request $request, $type, $id)
    {
        try 
        {
            switch($type)
            {
                case 'rotavirus':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:RotaVirus')->find($id): null;
                    $form   = $this->createForm('rotavirus',$record);
                    break;
                case 'lab':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Rota\SiteLab')->findOrCreateNew($id);
                    $form   = $this->createForm('rotavirus_sitelab',$record);
                    break;
                case 'rrl':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Rota\ReferenceLab')->findOrCreateNew($id);
                    $form   = $this->createForm('rotavirus_referencelab',$record);
                    break;
                case 'nl':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Rota\NationalLab')->findOrCreateNew($id);
                    $form   = $this->createForm('rotavirus_nationallab',$record);
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
                    die("ERROR: ".$e->getMessage());
                    // TODO Flash service required
                    return array('form' => $form->createView(),'id'=>$id,'type'=>strtoupper($type));
                }

                // TODO Flash service required
                return $this->redirect($this->generateUrl("rotavirusIndex"));
            }
        }

        return array('form' => $form->createView(),'id'=>$id, 'type'=>strtoupper($type));
    }

    /**
     * @Route("/show/{id}",name="rotavirusShow")
     * @Template()
     */
    public function showAction($id)
    {
        return array('record' => $this->get('ns.model_manager')->getRepository('NSSentinelBundle:RotaVirus')->get($id));
    }
}
