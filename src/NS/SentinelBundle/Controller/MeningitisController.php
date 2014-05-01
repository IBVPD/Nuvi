<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use NS\SentinelBundle\Exceptions\NonExistentCase;
use NS\SentinelBundle\Form\Types\IBDCreateRoles;
use \Symfony\Component\Form\FormError;

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

        if($sc->isGranted('ROLE_SITE') || $sc->isGranted('ROLE_LAB') || $sc->isGranted('ROLE_RRL_LAB') || $sc->isGranted('ROLE_NL_LAB'))
            $t = array('header_template'=>'NSSentinelBundle:Meningitis:indexSiteHeader.html.twig', 'row_template'=>'NSSentinelBundle:Meningitis:indexSiteRow.html.twig');
        else if($sc->isGranted('ROLE_COUNTRY'))
            $t = array('header_template'=>'NSSentinelBundle:Meningitis:indexCountryHeader.html.twig', 'row_template'=>'NSSentinelBundle:Meningitis:indexCountryRow.html.twig');
        else if($sc->isGranted('ROLE_REGION'))
            $t = array('header_template'=>'NSSentinelBundle:Meningitis:indexRegionHeader.html.twig', 'row_template'=>'NSSentinelBundle:Meningitis:indexRegionRow.html.twig');

        $createForm = ($sc->isGranted('ROLE_CAN_CREATE')) ? $this->createForm('create_ibd')->createView():null;

        return array('pagination' => $pagination, 't' => $t, 'form' => $this->createForm('results_per_page')->createView(),'filterForm'=>$filterForm->createView(),'createForm'=>$createForm);
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/create",name="meningitisCreate")
     * @Template()
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm('create_ibd');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $dbId   = $form->get('id')->getData();
            $caseId = $form->get('caseId')->getData();
            $type   = $form->get('type')->getData();

            $em         = $this->get('ns.model_manager');
            $meningCase = $em->getRepository('NSSentinelBundle:Meningitis')->findOrCreate($caseId,$dbId);

            if(!$meningCase->getId())
            {
                if($form->has('site'))
                    $site = $form->get('site')->getData();
                else
                    $site = $this->get('ns.sentinel.sites')->getSite();

                $meningCase->setSite($site);
            }

            switch($type->getValue())
            {
                case IBDCreateRoles::IBD:
                    $res = $this->redirect($this->generateUrl('meningitisEdit',array('id' => $meningCase->getId())));
                    break;
                case IBDCreateRoles::SITE:
                    $res = $this->redirect($this->generateUrl('meningitisLabEdit',array('id' => $meningCase->getId())));
                    break;
                case IBDCreateRoles::RRL:
                    $meningCase->setSentToReferenceLab(true);
                    $res = $this->redirect($this->generateUrl('meningitisRRLEdit',array('id' => $meningCase->getId())));
                    break;
                case IBDCreateRoles::NL:
                    $meningCase->setSentToNationalLab(true);
                    $res = $this->redirect($this->generateUrl('meningitisNLEdit',array('id' => $meningCase->getId())));
                    break;
                default:
                    $res = $this->redirect($this->generateUrl('meningitisIndex'));
                    break;
            }

            $em->persist($meningCase);
            $em->flush();

            return $res;
        }

        return $this->redirect($this->generateUrl('meningitisIndex'));
    }

    /**
     * @Route("/edit/{id}",name="meningitisEdit",defaults={"id"=null})
     * @Template()
     */
    public function editAction(Request $request,$id = null)
    {
        return $this->edit($request,'meningitis',$id);
    }

    /**
     * @Route("/rrl/edit/{id}",name="meningitisRRLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:Meningitis:editBaseLab.html.twig")
     */
    public function editRRLAction(Request $request,$id = null)
    {
        return $this->edit($request, 'rrl', $id);
    }

    /**
     * @Route("/nl/edit/{id}",name="meningitisNLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:Meningitis:editBaseLab.html.twig")
     */
    public function editNLAction(Request $request,$id = null)
    {
        return $this->edit($request, 'nl', $id);
    }

    /**
     * @Route("/lab/edit/{id}",name="meningitisLabEdit",defaults={"id"=null})
     * @Template()
     */
    public function editLabAction(Request $request,$id = null)
    {
        return $this->edit($request, 'lab', $id);
    }

    private function edit(Request $request, $type, $id = null)
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
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:SiteLab')->findOrCreateNew($id): null;
                    $form   = $this->createForm('meningitis_sitelab',$record);
                    break;
                case 'rrl':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:ReferenceLab')->findOrCreateNew($id): null;
                    $form   = $this->createForm('meningitis_referencelab',$record);
                    break;
                case 'nl':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:NationalLab')->findOrCreateNew($id): null;
                    $form   = $this->createForm('meningitis_nationallab',$record);
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

                if($type != 'meningitis')
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
                return $this->redirect($this->generateUrl("meningitisIndex"));
            }
        }

        return array('form' => $form->createView(),'id'=>$id, 'type'=>strtoupper($type));
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
