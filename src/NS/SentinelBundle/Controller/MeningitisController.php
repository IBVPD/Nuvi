<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use NS\SentinelBundle\Exceptions\NonExistentCase;
use NS\SentinelBundle\Form\Types\CreateRoles;
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

        $filterForm = $this->createForm('ibd_filter_form');
        $filterForm->submit($request);

        if($filterForm->isValid() && $filterForm->isSubmitted())
        {
            $query = $this->get('ns.model_manager')
                          ->getRepository('NSSentinelBundle:Meningitis')
                          ->getFilterQueryBuilder();

            // build the query from the given form object
            $qb    = $this->get('lexik_form_filter.query_builder_updater');
            $qb->addFilterConditions($filterForm, $query);
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
                case CreateRoles::BASE:
                    $res = 'meningitisEdit';
                    break;
                case CreateRoles::SITE:
                    $res = 'meningitisLabEdit';
                    break;
                case CreateRoles::RRL:
                    /*
                     * Only create a sitelab when this is a new case otherwise we're in an error condition
                     * Meaning that a site lab has already been created but
                     */
                    if(!$meningCase->getId() || ($meningCase->getId() && !$meningCase->hasSiteLab()))
                    {
                        $siteLab = new \NS\SentinelBundle\Entity\IBD\SiteLab();
                        $siteLab->setSentToReferenceLab(true);
                        $meningCase->setSiteLab($siteLab);
                    }
                    $res = 'meningitisRRLEdit';
                    break;
                case CreateRoles::NL:
                    if(!$meningCase->getId() || ($meningCase->getId() && !$meningCase->hasSiteLab()))
                    {
                        $siteLab = new \NS\SentinelBundle\Entity\IBD\SiteLab();
                        $siteLab->setSentToNationalLab(true);
                        $meningCase->setSiteLab($siteLab);
                    }
                    $res = 'meningitisNLEdit';
                    break;
                default:
                    $res = 'meningitisIndex';
                    break;
            }

            $em->persist($meningCase);
            $em->flush();

            return $this->redirect($this->generateUrl($res,array('id' => $meningCase->getId())));
        }

        return $this->redirect($this->generateUrl('meningitisIndex'));
    }

    /**
     * @Route("/edit/{id}",name="meningitisEdit",defaults={"id"=null})
     * @Template()
     */
    public function editAction(Request $request,$id = null)
    {
        return $this->edit($request,'ibd',$id);
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
                case 'ibd':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Meningitis')->find($id): null;
                    $form   = $this->createForm('ibd',$record);
                    break;
                case 'lab':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD\SiteLab')->findOrCreateNew($id): null;
                    $form   = $this->createForm('ibd_sitelab',$record);
                    break;
                case 'rrl':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD\ReferenceLab')->findOrCreateNew($id): null;
                    $form   = $this->createForm('ibd_referencelab',$record);
                    break;
                case 'nl':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD\NationalLab')->findOrCreateNew($id): null;
                    $form   = $this->createForm('ibd_nationallab',$record);
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

                if($type != 'ibd')
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
        try
        {
            return array('record' => $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Meningitis')->get($id));
        }
        catch (NonExistentCase $ex) 
        {
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig',array('message' => $ex->getMessage()));
        }
    }
}
