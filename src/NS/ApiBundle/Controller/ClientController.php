<?php

namespace NS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of ClientController
 *
 * @author gnat
 */
class ClientController extends Controller
{
    /**
     * @Route("/{_locale}/createClient",name="ApiCreateClient")
     */
    public function createClient()
    {
        $clientManager = $this->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setName('Name');
        $client->setRedirectUris(array('http://nuvi.noblet.ca','http://nuvi.noblet.ca/app_dev.php'));
        $client->setAllowedGrantTypes(array('token', 'authorization_code'));
        $clientManager->updateClient($client);

        return $this->redirect($this->generateUrl('fos_oauth_server_authorize', array(
                                                                                    'client_id'     => $client->getPublicId(),
                                                                                    'redirect_uri'  => 'http://nuvi.noblet.ca/app_dev.php',
                                                                                    'response_type' => 'code'
                                                                                    )));

        die(sprintf('Added a new client with name <info>%s</info> and public id <info>%s</info>',$client->getName(), $client->getPublicId()));
//        return $this->redirect($this->generateUrl("rotavirusIndex"));
    }
}
