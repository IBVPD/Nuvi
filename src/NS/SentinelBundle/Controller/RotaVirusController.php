<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
use \NS\SentinelBundle\Form\Types\CreateRoles;
use \Symfony\Component\Form\FormError;
use NS\SentinelBundle\Entity\Rota\Lab;

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
            $qb    = $this->get('lexik_form_filter.query_builder_updater');
            $qb->addFilterConditions($filterForm, $query, 'm');
        }
        else
            $query = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:RotaVirus")->getLatestQuery();

        $pagination = $paginator->paginate( $query,
                                            $request->query->get('page',1),
                                            $request->getSession()->get('result_per_page',10) );

        $sc = $this->get('security.context');

        if($sc->isGranted('ROLE_SITE') || $sc->isGranted('ROLE_LAB'))
            $t = array('header_template'=>'NSSentinelBundle:RotaVirus:indexSiteHeader.html.twig', 'row_template'=>'NSSentinelBundle:RotaVirus:indexSiteRow.html.twig');
        else if($sc->isGranted('ROLE_COUNTRY'))
            $t = array('header_template'=>'NSSentinelBundle:RotaVirus:indexCountryHeader.html.twig', 'row_template'=>'NSSentinelBundle:RotaVirus:indexCountryRow.html.twig');
        else if($sc->isGranted('ROLE_REGION'))
            $t = array('header_template'=>'NSSentinelBundle:RotaVirus:indexRegionHeader.html.twig', 'row_template'=>'NSSentinelBundle:RotaVirus:indexRegionRow.html.twig');

        $createForm = ($sc->isGranted('ROLE_CAN_CREATE')) ? $this->createForm('create_rotavirus')->createView():null;

        return array(
                    'pagination' => $pagination,
                    't'          => $t,
                    'form'       => $this->createForm('results_per_page')->createView(),
                    'filterForm' => $filterForm->createView(),
                    'createForm' => $createForm);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
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
            $em     = $this->get('ns.model_manager');
            $case   = $em->getRepository('NSSentinelBundle:RotaVirus')->findOrCreate($caseId);

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
                case CreateRoles::LAB:
                    $res = 'rotavirusLabEdit';
                    break;
                default:
                    $res = 'rotavirusIndex';
                    break;
            }

            $em->persist($case);
            $em->flush();

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
            $record = $id ? $this->get('ns.model_manager')->getRepository('NSSentinelBundle:RotaVirus')->find($id): null;

            switch($type)
            {
                case 'rotavirus':
                    $form   = $this->createForm('rotavirus',$record);
                    break;
                case 'outcome':
                    $form   = $this->createForm('rotavirus_outcome',$record);
                    break;
                case 'lab':
                    $form   = $this->createForm('rotavirus_lab',$record);
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
                catch(\Doctrine\DBAL\DBALException $e)
                {
                    // TODO Flash service required
                    if($e->getPrevious()->getCode() === '23000')
                        $form->addError(new FormError ("The case id already exists for this site!".$e->getPrevious()->getMessage()));
                    else
                        die("ERROR: ".$e->getMessage());

                    return array('form' => $form->createView(),'id'=>$id, 'type'=>strtoupper($type));
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
