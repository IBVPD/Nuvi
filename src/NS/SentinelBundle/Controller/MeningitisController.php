<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use NS\SentinelBundle\Form\MeningitisType;

class MeningitisController extends Controller
{
    /**
     * @Route("/",name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $rows = $this->getDoctrine()->getManager()->getRepository("NSSentinelBundle:Meningitis")->findAll();

        return array('rows'=>$rows);
    }

    /**
     * @Route("/edit/{id}",name="createOrEdit",defaults={"id"=null})
     * @Template()
     */
    public function editAction(Request $request,$id)
    {
        $record = ($id > 0) ? $this->getDoctrine()->getManager()->getRepository('NSSentinelBundle:Meningitis')->find($id): $id;
        $form   = $this->createForm(new MeningitisType(),$record);

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $record = $form->getData();
                $em->persist($record);
                $em->flush();

                return $this->redirect($this->generateUrl("homepage"));
            }
        }

        return array('form' => $form->createView(),'id'=>$id);
    }
}
