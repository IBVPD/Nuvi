<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use \NS\SentinelBundle\Form\MeningitisType;
use \NS\SentinelBundle\Form\MeningitisSearch;

/**
 * @Route("/{_locale}/meningitis")
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
        
        return array('rows' => $rows,'form' => $form->createView());
    }

    /**
     * @Route("/create",name="meningitisCreate")
     * @Route("/edit/{id}",name="meningitisEdit",defaults={"id"=null})
     * @Template()
     */
    public function editAction(Request $request,$id = null)
    {
        $record = ($id > 0) ? $this->getDoctrine()->getManager()->getRepository('NSSentinelBundle:Meningitis')->find($id): null;
        $form   = $this->createForm('meningitis',$record);

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $record = $form->getData();
                $em->persist($record);
                $em->flush();

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
                               ->get($params['caseId']);

                return $this->render('NSSentinelBundle:Meningitis:show.html.twig', array('record' => $record));
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
                    ->getAutocomplete('NSSentinelBundle:Meningitis','caseId');
    }
}
