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
    public function indexAction()
    {
        $rows = $this->get('ns.model_manager')->getRepository("NSSentinelBundle:Meningitis")->getLatest();
        $form = $this->createForm(new MeningitisSearch());
        $sc   = $this->get('security.context');

        if($sc->isGranted('ROLE_SITE'))
            $t = array('template' => 'NSSentinelBundle:Meningitis:index-action.html.twig', 'action' => 'meningitisEdit');
        else if($sc->isGranted('ROLE_LAB'))
            $t = array('template' => 'NSSentinelBundle:Meningitis:index-lab-action.html.twig', 'action' => 'meningitisLabEdit');
        else if($sc->isGranted('ROLE_RRL_LAB'))
            $t = array('template' => 'NSSentinelBundle:Meningitis:index-rrl-action.html.twig', 'action' => 'meningitisRRLCreate');
        else if($sc->isGranted('ROLE_REGION'))
            $t = array('template' => 'NSSentinelBundle:Meningitis:index-action.html.twig', 'action' => '');

        return array('rows' => $rows,'form' => $form->createView(),'t'=>$t);
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

    /**
     * @Route("/search",name="meningitisSearch")
     * @Method({"POST"})
     */
    public function searchAction(Request $request)
    {
        $form = $this->createForm(new MeningitisSearch());
        $form->submit($request);

        if($form->isValid())
        {
            try
            {
                $params = $request->request->get('meningitis_search');
                $record = $this->get('ns.model_manager')
                               ->getRepository('NSSentinelBundle:Meningitis')
                               ->search($params['id']);

                if($record instanceof \NS\SentinelBundle\Entity\Meningitis)
                    return $this->render('NSSentinelBundle:Meningitis:show.html.twig', array('record' => $record));
                else
                {
                    $sc = $this->get('security.context');
                    if($sc->isGranted('ROLE_SITE'))
                        $t = array('template' => 'NSSentinelBundle:Meningitis:index-action.html.twig', 'action' => 'meningitisEdit','canCreate'=>true);
                    else if($sc->isGranted('ROLE_LAB'))
                        $t = array('template' => 'NSSentinelBundle:Meningitis:index-lab-action.html.twig', 'action' => 'meningitisLabEdit','canCreate'=>false);
                    else if($sc->isGranted('ROLE_RRL_LAB'))
                        $t = array('template' => 'NSSentinelBundle:Meningitis:index-rrl-action.html.twig', 'action' => 'meningitisRRLCreate','canCreate'=>false);
                    else
                        $t = array('template' => 'NSSentinelBundle:Meningitis:index-rrl-action.html.twig', 'action' => 'meningitisRRLCreate','canCreate'=>false);

                    return $this->render('NSSentinelBundle:Meningitis:index.html.twig',array('rows' => $record,'form' => $form->createView(),'t'=>$t));
                }
            }
            catch(\Exception $e)
            {
                throw $this->createNotFoundException(__LINE__.' Case does not exist: '.$e->getMessage());
            }
        }

        throw $this->createNotFoundException(__LINE__.' Case does not exist: '.$form->getErrorsAsString());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/ajax/meningitis",name="ajaxMeningitisAutoComplete")
     */
    public function ajaxAutoComplete()
    {
        return $this->get('ns.ajax_autocompleter')
                    ->getAutocomplete('NSSentinelBundle:Meningitis','id');
    }
}
