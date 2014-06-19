<?php

namespace NS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use NS\ApiBundle\Form\Model\Authorize;
use NS\ApiBundle\Entity\Client;
use OAuth2\OAuth2ServerException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

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

        $ret1 = $authorizeClient->getAccessToken($request->query->get('code'));
        $em   = $this->get('doctrine.orm.entity_manager');

        $remoteToken = new \NS\ApiBundle\Entity\RemoteToken();
        $remoteToken->setAccessToken($ret1['access_token']);
        $remoteToken->setRefreshToken($ret1['refresh_token']);
        $remoteToken->setExpiry(time()+$ret1['expires_in']);
        $remoteToken->setUser($em->getReference(get_class($this->getUser()),$this->getUser()->getId()));
        $remoteToken->setRemoteEndpoint('http://nuvi.noblet.ca/api/v1/');

        $em->persist($remoteToken);
        $em->flush();

        return $this->redirect($this->generateUrl('authTest'));
    }

    /**
     * @Route("/authorizeTest",name="authTest")
     */
    public function authTestAction()
    {
        $em              = $this->get('doctrine.orm.entity_manager');
        $repo            = $em->getRepository('NSApiBundle:RemoteToken');
        $tokens          = $repo->findByUser($this->getUser());
        $refreshClient   = $this->get('ns_api_client.refresh_token');
        $r               = array();

        foreach($tokens as $token)
        {
            if($token->isExpired())
            {
                $u   = array('OldAccessToken'=>$token->getAccessToken(),'OldRefreshToken'=>$token->getRefreshToken());
                $ret = $this->refresh($refreshClient,$token);
                $em->persist($token);
                $u['NewAccessToken']  = $token->getAccessToken();
                $u['OldRefreshToken'] = $token->getRefreshToken();

                $r[] = $u;
                $r[] = $r;
            }

            $refreshClient->setAccessToken($token->getAccessToken());
            $ret = $refreshClient->fetch('http://nuvi.noblet.ca/api/v1/articles');
            if($ret['code'] == \FOS\RestBundle\Util\Codes::HTTP_UNAUTHORIZED)
            {
                $r[] = $ret;

                $r[] = $this->refresh($refreshClient, $token);
                $em->persist($token);
                $refreshClient->setAccessToken($token->getAccessToken());

                $ret = $refreshClient->fetch('http://nuvi.noblet.ca/api/v1/articles');
            }

            $r[] = $ret;
        }

        $em->flush();

        return new Response("Returned: <pre>".print_r($r,true)."</pre>");
    }

    private function refresh($client,&$token)
    {
        try
        {
            $ret = $client->getAccessToken($token->getRefreshToken());
            if(isset($ret['access_token']))
            {
                $token->setAccessToken($ret['access_token']);
                $token->setRefreshToken($ret['refresh_token']);
                $token->setExpiry(time()+$ret['expires_in']);
            }
            else
                die("HERE! ".__LINE__.' '.print_r($ret,true));
        }
        catch(OAuth2\Exception $e)
        {
            $ret = array('unable to get access token');
        }

        return $ret;
    }
}