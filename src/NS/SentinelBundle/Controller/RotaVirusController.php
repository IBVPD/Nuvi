<?php

namespace NS\SentinelBundle\Controller;

use \Doctrine\DBAL\DBALException;
use \NS\SentinelBundle\Entity\Rota\SiteLab;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
use \NS\SentinelBundle\Form\Types\CreateRoles;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\Form\FormError;
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
        $paginator  = $this->get('knp_paginator');

        $filterForm = $this->createForm('rotavirus_filter_form');
        $filterForm->handleRequest($request);

        if($filterForm->isValid())
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

        $pagination = $paginator->paginate( $query,
                                            $request->query->get('page',1),
                                            $request->getSession()->get('result_per_page',10) );

        $securityContext = $this->get('security.context');

        if ($securityContext->isGranted('ROLE_SITE') || $securityContext->isGranted('ROLE_LAB') || $securityContext->isGranted('ROLE_RRL_LAB') || $securityContext->isGranted('ROLE_NL_LAB'))
            $template = array('header_template'=>'NSSentinelBundle:RotaVirus:indexSiteHeader.html.twig', 'row_template'=>'NSSentinelBundle:RotaVirus:indexSiteRow.html.twig');
        else if($securityContext->isGranted('ROLE_COUNTRY'))
            $template = array('header_template'=>'NSSentinelBundle:RotaVirus:indexCountryHeader.html.twig', 'row_template'=>'NSSentinelBundle:RotaVirus:indexCountryRow.html.twig');
        else if($securityContext->isGranted('ROLE_REGION'))
            $template = array('header_template'=>'NSSentinelBundle:RotaVirus:indexRegionHeader.html.twig', 'row_template'=>'NSSentinelBundle:RotaVirus:indexRegionRow.html.twig');

        $createForm = ($securityContext->isGranted('ROLE_CAN_CREATE')) ? $this->createForm('create_rotavirus')->createView():null;

        return array(
                    'pagination' => $pagination,
                    't'          => $template,
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
        $form = $this->createForm('create_rotavirus');
        $form->handleRequest($request);

        if($form->isValid())
        {
            $caseId = $form->get('caseId')->getData();
            $type   = $form->get('type')->getData();
            $entityMgr = $this->get('ns.model_manager');
            $case   = $entityMgr->getRepository('NSSentinelBundle:RotaVirus')->findOrCreate($caseId);

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
                    $res = 'rotavirusEdit';
                    break;
                case CreateRoles::SITE:
                    $res = 'rotavirusLabEdit';
                    break;
                case CreateRoles::RRL:
                    /*
                     * Only create a sitelab when this is a new case otherwise we're in an error condition
                     * Meaning that a site lab has already been created but
                     */
                    if (!$case->getId() || ($case->getId() && !$case->hasSiteLab()))
                    {
                        $siteLab = new SiteLab();
                        $siteLab->setSentToReferenceLab(true);
                        $case->setSiteLab($siteLab);
                    }
                    $res = 'rotavirusRRLEdit';
                    break;
                case CreateRoles::NL:
                    if (!$case->getId() || ($case->getId() && !$case->hasSiteLab()))
                    {
                        $siteLab = new SiteLab();
                        $siteLab->setSentToNationalLab(true);
                        $case->setSiteLab($siteLab);
                    }
                    $res = 'rotavirusNLEdit';
                    break;
                default:
                    $res = 'rotavirusIndex';
                    break;
            }

            $entityMgr->persist($case);
            $entityMgr->flush();

            return $this->redirect($this->generateUrl($res,array('id' => $case->getId())));
        }

        return $this->redirect($this->generateUrl('rotavirusIndex'));
    }

    /**
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
        return $this->edit($request, 'lab', $id);
    }

    /**
     * @Route("/rrl/edit/{id}",name="rotavirusRRLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:RotaVirus:editBaseLab.html.twig")
     */
    public function editRRLAction(Request $request, $id = null)
    {
        return $this->edit($request, 'rrl', $id);
    }

    /**
     * @Route("/nl/edit/{id}",name="rotavirusNLEdit",defaults={"id"=null})
     * @Template("NSSentinelBundle:RotaVirus:editBaseLab.html.twig")
     */
    public function editNLAction(Request $request, $id = null)
    {
        return $this->edit($request, 'nl', $id);
    }

    /**
     * @Route("/outcome/edit/{id}",name="rotavirusOutcomeEdit",defaults={"id"=null})
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
                case 'rotavirus':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:RotaVirus')->find($id) : null;
                    $form   = $this->createForm('rotavirus',$record);
                    break;
                case 'outcome':
                    $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:RotaVirus')->find($id) : null;
                    $form   = $this->createForm('rotavirus_outcome', $record);
                    break;
                case 'lab':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Rota\SiteLab')->findOrCreateNew($id);
                    $form   = $this->createForm('rotavirus_lab', $record);
                    break;
                case 'rrl':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Rota\ReferenceLab')->findOrCreateNew($id);
                    $form   = $this->createForm('rotavirus_referencelab', $record);
                    break;
                case 'nl':
                    $record = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Rota\NationalLab')->findOrCreateNew($id);
                    $form   = $this->createForm('rotavirus_nationallab', $record);
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

        $form->handleRequest($request);
        if($form->isValid())
        {
            $entityMgr = $this->getDoctrine()->getManager();
            $record = $form->getData();
            $entityMgr->persist($record);

            try
            {
                $entityMgr->flush();
            }
            catch (DBALException $e)
            {
                // TODO Flash service required
                if($e->getPrevious()->getCode() === '23000')
                    $form->addError(new FormError("The case id already exists for this site! " . $e->getPrevious()->getMessage()));
                else
                    die("ERROR: ".$e->getMessage());

                return array('form' => $form->createView(),'id'=>$id, 'type'=>strtoupper($type));
            }

            // TODO Flash service required
            return $this->redirect($this->generateUrl("rotavirusIndex"));
        }

        return array('form' => $form->createView(),'id'=>$id, 'type'=>strtoupper($type));
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
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig',array('message' => $ex->getMessage()));
        }
    }
}
