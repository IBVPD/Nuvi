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
 */
class ClientController extends Controller
{
    /**
     * @Route("/createClient",name="ApiCreateClient")
     * @Template()
     */
    public function createAction(Request $request)
    {
//        $clientManager = $this->get('fos_oauth_server.client_manager.default');
//        $client        = new \NS\ApiBundle\Entity\Client();//$clientManager->createClient();
        $form = $this->createForm(new \NS\ApiBundle\Form\ClientType());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            $client = $form->getData();
            $em->persist($client);
            $em->flush();

            return $this->redirect($this->generateUrl('fos_oauth_server_authorize', array(
                                                                                'client_id'     => $client->getPublicId(),
                                                                                'redirect_uri'  => $client->getRedirectUris()[0],
                                                                                'response_type' => 'token'
                                                                                )));
        }

        return array('form'=>$form->createView());
    }
}
