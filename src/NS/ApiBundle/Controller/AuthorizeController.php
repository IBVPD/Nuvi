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
        $em      = $this->get('doctrine.orm.entity_manager');
        $repo    = $em->getRepository('NSApiBundle:Remote');
        $remotes = $repo->findByUser($this->getUser());

        // TODO handle more than one remote... how would we know which client_id/secret is being authorized...
        // Ask the user since they started off the authorization so should know.

        if(count($remotes)>1)
            throw new \Doctrine\ORM\UnexpectedResultException("We really only support one remote per user at the moment");

        $remote = current($remotes);

        $authorizeClient = $this->get('oauth2.client');
        $authorizeClient->setRemote($remote);

        if (!$request->query->get('code'))
            return new RedirectResponse($authorizeClient->getAuthenticationUrl());

        if( $authorizeClient->getAccessTokenByAuthorizationCode($request->query->get('code')))
            return $this->redirect($this->generateUrl('authTest'));
    }

    /**
     * @Route("/authorizeTest",name="authTest")
     */
    public function authTestAction()
    {
        $em     = $this->get('doctrine.orm.entity_manager');
        $repo   = $em->getRepository('NSApiBundle:Remote');
        $tokens = $repo->findByUser($this->getUser());
        $client = $this->get('oauth2.client');
        $r      = array();

        foreach($tokens as $token)
        {
            $client->setRemote($token);
            $r[] = $client->fetch('http://nuvi.noblet.ca/api/v1/articles');
        }

        return new Response("Returned: <pre>".print_r($r,true)."</pre>");
    }
}