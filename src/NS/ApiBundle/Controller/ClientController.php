<?php

namespace NS\ApiBundle\Controller;

use NS\ApiBundle\Form\ClientType;
use NS\ApiBundle\Form\RemoteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $user      = $this->getUser();
        $clients   = $entityMgr->getRepository('NSApiBundle:Client')
                      ->createQueryBuilder('c')
                      ->where('c.user = :user')
                      ->setParameter('user', $entityMgr->getReference(get_class($user), $user->getId()))
                      ->getQuery()
                      ->getResult();

        $remotes   = $entityMgr->getRepository('NSApiBundle:Remote')
                      ->createQueryBuilder('r')
                      ->where('r.user = :user')
                      ->setParameter('user', $entityMgr->getReference(get_class($user), $user->getId()))
                      ->getQuery()
                      ->getResult();

        return ['clients'=>$clients,'remotes'=>$remotes];
    }

    /**
     * @Route("/client/create",name="ApiCreateClient")
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(ClientType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityMgr     = $this->get('doctrine.orm.entity_manager');
            $client = $form->getData();
            $client->setUser($entityMgr->getReference('NSSentinelBundle:User', $this->getUser()->getId()));
            $entityMgr->persist($client);
            $entityMgr->flush();

            return $this->redirect($this->generateUrl('ns_api_dashboard'));
        }

        return ['form'=>$form->createView(),'route'=>'ApiCreateClient'];
    }

    /**
     * @Route("/client/edit/{objId}",name="ApiEditClient")
     * @Template("NSApiBundle:Client:create.html.twig")
     * @param Request $request
     * @param $objId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function editAction(Request $request, $objId)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $user      = $this->getUser();
        $client    = $entityMgr->getRepository('NSApiBundle:Client')
                     ->createQueryBuilder('c')->where('c.user = :user AND c.id = :id')
                     ->setParameters(['id'=>$objId, 'user'=>$entityMgr->getReference(get_class($user), $user->getId())])
                     ->getQuery()
                     ->getSingleResult();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $client = $form->getData();
            $entityMgr->persist($client);
            $entityMgr->flush();

            return $this->redirect($this->generateUrl('ns_api_dashboard'));
        }

        return ['form'=>$form->createView(),'route'=>'ApiEditClient','id'=>$objId];
    }

    /**
     * @Route("/client/delete/{objId}",name="ApiDeleteClient")
     * @param $objId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function deleteAction($objId)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $user   = $this->getUser();
        $client = $entityMgr->getRepository('NSApiBundle:Client')
                     ->createQueryBuilder('c')->where('c.user = :user AND c.id = :id')
                     ->setParameters(['id'=>$objId, 'user'=>$entityMgr->getReference(get_class($user), $user->getId())])
                     ->getQuery()
                     ->getSingleResult();

        try {
            $entityMgr->remove($client);
            $entityMgr->flush();
            $this->get('ns_flash')->addSuccess(null, null, "Successfully deleted api client");
        } catch (\Exception $e) {
            $this->get('ns_flash')->addError(null, null, "Unable to delete api client");
        }

        return $this->redirect($this->generateUrl('ns_api_dashboard'));
    }

    /**
     * @Route("/remote/create",name="ApiCreateRemote")
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createRemoteAction(Request $request)
    {
        $form = $this->createForm(RemoteType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityMgr     = $this->get('doctrine.orm.entity_manager');
            $client = $form->getData();
            $client->setUser($entityMgr->getReference('NSSentinelBundle:User', $this->getUser()->getId()));
            $entityMgr->persist($client);
            $entityMgr->flush();

            return $this->redirect($this->generateUrl('ns_api_dashboard'));
        }

        return ['form'=>$form->createView(),'route'=>'ApiCreateRemote'];
    }

    /**
     * @Route("/remote/edit/{objId}",name="ApiEditRemote")
     * @Template("NSApiBundle:Client:createRemote.html.twig")
     * @param Request $request
     * @param $objId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editRemoteAction(Request $request, $objId)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $user   = $this->getUser();
        $remote = $entityMgr->getRepository('NSApiBundle:Remote')
                     ->createQueryBuilder('c')->where('c.user = :user AND c.id = :id')
                     ->setParameters(['id'=>$objId, 'user'=>$entityMgr->getReference(get_class($user), $user->getId())])
                     ->getQuery()
                     ->getSingleResult();

        $form = $this->createForm(RemoteType::class, $remote);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $client = $form->getData();
            $client->setUser($entityMgr->getReference('NSSentinelBundle:User', $this->getUser()->getId()));
            $entityMgr->persist($client);
            $entityMgr->flush();

            return $this->redirect($this->generateUrl('ns_api_dashboard'));
        }

        return ['form'=>$form->createView(),'route'=>'ApiEditRemote','id'=>$objId];
    }

    /**
     * @Route("/remote/delete/{objId}",name="ApiDeleteRemote")
     * @param $objId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteRemoteAction($objId)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $user   = $this->getUser();
        $remote = $entityMgr->getRepository('NSApiBundle:Remote')
                     ->createQueryBuilder('c')->where('c.user = :user AND c.id = :id')
                     ->setParameters(['id'=>$objId, 'user'=>$entityMgr->getReference(get_class($user), $user->getId())])
                     ->getQuery()
                     ->getSingleResult();
        try {
            $entityMgr->remove($remote);
            $entityMgr->flush();
            $this->get('ns_flash')->addSuccess(null, null, "Successfully deleted remote server");
        } catch (\Exception $e) {
            $this->get('ns_flash')->addError(null, null, "Unable to delete remote server");
        }

        return $this->redirect($this->generateUrl('ns_api_dashboard'));
    }
}
