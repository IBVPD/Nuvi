<?php

namespace NS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ClientController
 *
 * @author gnat
 * @Route("/oauth")
 */
class ClientController extends Controller
{
    /**
     * @Route("/dashboard",name="ns_api_dashboard")
     * @Template()
     */
    public function dashboardAction()
    {
        $em      = $this->get('doctrine.orm.entity_manager');
        $user    = $this->getUser();
        $clients = $em->getRepository('NSApiBundle:Client')
                      ->createQueryBuilder('c')
                      ->where('c.user = :user')
                      ->setParameter('user',$em->getReference(get_class($user),$user->getId()))
                      ->getQuery()
                      ->getResult();

        $remotes = $em->getRepository('NSApiBundle:Remote')
                      ->createQueryBuilder('r')
                      ->where('r.user = :user')
                      ->setParameter('user',$em->getReference(get_class($user),$user->getId()))
                      ->getQuery()
                      ->getResult();

        return array('clients'=>$clients,'remotes'=>$remotes);
    }

    /**
     * @Route("/client/create",name="ApiCreateClient")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm('CreateApiClient');
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em     = $this->get('doctrine.orm.entity_manager');
            $client = $form->getData();
            $client->setUser($em->getReference('NSSentinelBundle:User',$this->getUser()->getId()));
            $em->persist($client);
            $em->flush();

            return $this->redirect($this->generateUrl('ns_api_dashboard'));
        }

        return array('form'=>$form->createView(),'route'=>'ApiCreateClient');
    }

    /**
     * @Route("/client/edit/{id}",name="ApiEditClient")
     * @Template("NSApiBundle:Client:create.html.twig")
     */
    public function editAction(Request $request,$id)
    {
        $em     = $this->get('doctrine.orm.entity_manager');
        $user   = $this->getUser();
        $client = $em->getRepository('NSApiBundle:Client')
                     ->createQueryBuilder('c')->where('c.user = :user AND c.id = :id')
                     ->setParameters(array('id'=>$id, 'user'=>$em->getReference(get_class($user),$user->getId())))
                     ->getQuery()
                     ->getSingleResult();
        $form = $this->createForm('CreateApiClient',$client);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $client = $form->getData();
            $em->persist($client);
            $em->flush();

            return $this->redirect($this->generateUrl('ns_api_dashboard'));
        }

        return array('form'=>$form->createView(),'route'=>'ApiEditClient','id'=>$id);
    }

    /**
     * @Route("/client/delete/{id}",name="ApiDeleteClient")
     */
    public function deleteAction($id)
    {
        $em     = $this->get('doctrine.orm.entity_manager');
        $user   = $this->getUser();
        $client = $em->getRepository('NSApiBundle:Client')
                     ->createQueryBuilder('c')->where('c.user = :user AND c.id = :id')
                     ->setParameters(array('id'=>$id, 'user'=>$em->getReference(get_class($user),$user->getId())))
                     ->getQuery()
                     ->getSingleResult();

        try
        {
            $em->remove($client);
            $em->flush();
            $this->get('ns_flash')->addSuccess(null, null, "Successfully deleted api client");
        }
        catch(\Exception $e)
        {
            $this->get('ns_flash')->addError(null, null, "Unable to delete api client");
        }

        return $this->redirect($this->generateUrl('ns_api_dashboard'));
    }

    /**
     * @Route("/remote/create",name="ApiCreateRemote")
     * @Template()
     */
    public function createRemoteAction(Request $request)
    {
        $form = $this->createForm('CreateApiRemote');
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em     = $this->get('doctrine.orm.entity_manager');
            $client = $form->getData();
            $client->setUser($em->getReference('NSSentinelBundle:User',$this->getUser()->getId()));
            $em->persist($client);
            $em->flush();

            return $this->redirect($this->generateUrl('ns_api_dashboard'));
        }

        return array('form'=>$form->createView(),'route'=>'ApiCreateRemote');
    }

    /**
     * @Route("/remote/edit/{id}",name="ApiEditRemote")
     * @Template("NSApiBundle:Client:createRemote.html.twig")
     */
    public function editRemoteAction(Request $request,$id)
    {
        $em     = $this->get('doctrine.orm.entity_manager');
        $user   = $this->getUser();
        $remote = $em->getRepository('NSApiBundle:Remote')
                     ->createQueryBuilder('c')->where('c.user = :user AND c.id = :id')
                     ->setParameters(array('id'=>$id, 'user'=>$em->getReference(get_class($user),$user->getId())))
                     ->getQuery()
                     ->getSingleResult();

        $form = $this->createForm('CreateApiRemote',$remote);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $client = $form->getData();
            $client->setUser($em->getReference('NSSentinelBundle:User',$this->getUser()->getId()));
            $em->persist($client);
            $em->flush();

            return $this->redirect($this->generateUrl('ns_api_dashboard'));
        }

        return array('form'=>$form->createView(),'route'=>'ApiEditRemote','id'=>$id);
    }

    /**
     * @Route("/remote/delete/{id}",name="ApiDeleteRemote")
     */
    public function deleteRemoteAction($id)
    {
        $em     = $this->get('doctrine.orm.entity_manager');
        $user   = $this->getUser();
        $remote = $em->getRepository('NSApiBundle:Remote')
                     ->createQueryBuilder('c')->where('c.user = :user AND c.id = :id')
                     ->setParameters(array('id'=>$id, 'user'=>$em->getReference(get_class($user),$user->getId())))
                     ->getQuery()
                     ->getSingleResult();
        try
        {
            $em->remove($remote);
            $em->flush();
            $this->get('ns_flash')->addSuccess(null, null, "Successfully deleted remote server");
        }
        catch(\Exception $e)
        {
            $this->get('ns_flash')->addError(null, null, "Unable to delete remote server");
        }


        return $this->redirect($this->generateUrl('ns_api_dashboard'));
    }
}
