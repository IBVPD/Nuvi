<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use NS\SentinelBundle\Form\MeningitisType;

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
        $rows = $this->getDoctrine()->getManager()->getRepository("NSSentinelBundle:Meningitis")->findAll();

        return array('rows'=>$rows);
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
}
