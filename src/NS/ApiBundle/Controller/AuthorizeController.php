<?php

namespace NS\ApiBundle\Controller;

use \Doctrine\ORM\UnexpectedResultException;
use \NS\ApiBundle\Entity\Client;
use \NS\ApiBundle\Form\Model\Authorize;
use \OAuth2\OAuth2ServerException;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Description of AuthorizeController
 *
 * @author gnat
 */
class AuthorizeController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     * @Route("/oauth/v2/auth",name="ApiClientAuthorization")
     * @throws NotFoundHttpException
     */
    public function authorizeAction(Request $request)
    {
        if (!$request->get('client_id'))
            throw new NotFoundHttpException(sprintf("Client id parameter %s is missing.", $request->get('client_id')));

        $clientManager = $this->get('fos_oauth_server.client_manager.default');
        $client        = $clientManager->findClientByPublicId($request->get('client_id'));

        if (!$client instanceof Client)
            throw new NotFoundHttpException(sprintf("Client %s is not found. '%s'", $request->get('client_id'), get_class($client)));

        $authorize   = new Authorize();
        $form        = $this->createForm('api_oauth_server_authorize', $authorize);
        $oauthServer = $this->get('fos_oauth_server.server');

        $form->handleRequest($request);
        if ($form->isValid())
        {
            try
            {
                $this->get('fos_oauth_server.auth_code_manager')->deleteExpired();
                $ref = $this->get('doctrine.orm.entity_manager')->getReference(get_class($this->getUser()), $this->getUser()->getId());
                return $oauthServer->finishClientAuthorization(true, $ref, $request, null);
            }
            catch (OAuth2ServerException $e)
            {
                return $e->getHttpResponse();
            }
        }

        return $this->render('NSApiBundle:Authorize:authorize.html.twig', array(
                'form'   => $form->createView(), 'client' => $client));
    }

    /**
     * @Route("/authorize", name="remoteAuthorizationCallback")
     */
    public function remoteAuthorizationCallbackAction(Request $request)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $repo      = $entityMgr->getRepository('NSApiBundle:Remote');
        $remotes   = $repo->findByUser($this->getUser());

        /**
         * @todo handle more than one remote
         *        - how would we know which client_id/secret is being authorized?
         *           - Ask the user since they started off the authorization so should know
         *           - Use a session var?
         * @todo we need to also somehow check that we're using the user the remote is linked to - another point for using a session var
         */
        if (count($remotes) > 1) {
            throw new UnexpectedResultException("We really only support one remote per user at the moment");
        }

        $remote          = current($remotes);
        $authorizeClient = $this->get('oauth2.client');
        $authorizeClient->setRemote($remote);

        if (!$request->query->get('code')) {
            return new RedirectResponse($authorizeClient->getAuthenticationUrl());
        }

        if ($authorizeClient->getAccessTokenByAuthorizationCode($request->query->get('code'))) {
            return $this->redirect($this->generateUrl('ns_api_dashboard'));
        }

        return $this->createNotFoundException("Unable to complete authorization");
    }
}