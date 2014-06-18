<?php

namespace NS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use NS\ApiBundle\Form\Model\Authorize;
use NS\ApiBundle\Entity\Client;
use OAuth2\OAuth2ServerException;

/**
 * Description of AuthorizeController
 *
 * @author gnat
 */
class AuthorizeController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/oauth/v2/auth",name="api_login_auth")
     */
    public function authorizeAction(Request $request)
    {
        if (!$request->get('client_id')) 
            throw new NotFoundHttpException("Client id parameter {$request->get('client_id')} is missing.");

        $clientManager = $this->get('fos_oauth_server.client_manager.default');
        $client        = $clientManager->findClientByPublicId($request->get('client_id'));

        if (!$client instanceof \NS\ApiBundle\Entity\Client)
            throw new NotFoundHttpException("Client {$request->get('client_id')} is not found.". get_class($client));

        $authorize    = new Authorize();
        $form         = $this->createForm('api_oauth_server_authorize',$authorize);
        $oauthServier = $this->get('fos_oauth_server.server');

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try
            {
                $ref = $this->get('doctrine.orm.entity_manager')->getReference(get_class($this->getUser()),$this->getUser()->getId());
                return $oauthServier->finishClientAuthorization(true,$ref,$request,null);
            }
            catch(OAuth2ServerException $e)
            {
                return $e->getHttpResponse();
            }
        }

        return $this->render('NSApiBundle:Authorize:authorize.html.twig', array('form' => $form->createView(),'client' => $client));
    }

    /**
     * @Route("/authorize", name="auth")
     */
    public function authAction(Request $request)
    {
        $authorizeClient = $this->container->get('ns_api_client.authorize_client');

        if (!$request->query->get('code'))
        {
            return new RedirectResponse($authorizeClient->getAuthenticationUrl());
        }

        $authorizeClient->getAccessToken($request->query->get('code'));

        return new Response($authorizeClient->fetch('http://nuvi.noblet.ca/api/articles'));
    }
}