<?php

namespace NS\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
//use FOS\OAuthServerBundle\Controller\AuthorizeController as BaseAuthorizeController;
use NS\ApiBundle\Form\Model\Authorize;
use NS\ApiBundle\Entity\Client;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of AuthorizeController
 *
 * @author gnat
 */
class AuthorizeController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/oauth/v2/auth",name="apiAuth")
     */
    public function authorizeAction(Request $request)
    {
        if (!$request->get('client_id')) 
            throw new NotFoundHttpException("Client id parameter {$request->get('client_id')} is missing.");

        $clientManager = $this->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->findClientByPublicId($request->get('client_id'));

        if (!($client instanceof Client))
            throw new NotFoundHttpException("Client {$request->get('client_id')} is not found.".  get_class($client));

//        $user        = $this->getUser();//container->get('security.context')->getToken()->getUser();
        $authorize    = new Authorize();
        $form         = $this->createForm('api_oauth_server_authorize',$authorize);
//        $formHandler = $this->container->get('ns_apioauth_server.authorize.form_handler');
        $oauthServier = $this->get('fos_oauth_server.server');
        
        
//        $this->form->setData($authorize);
//
//        if ($this->request->getMethod() == 'POST')
//        {
//            $this->form->bindRequest($this->request);
//
//            if ($this->form->isValid())
//            {
//                try
//                {
//                    $user = $this->context->getToken()->getUser();
//                    return $this->oauth2->finishClientAuthorization(true, $user, $this->request, null);
//                }
//                catch (OAuth2ServerException $e)
//                {
//                    return $e->getHttpResponse();
//                }
//            }
//        }
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try
            {
                return $oauthServier->finishClientAuthorization(true,$this->getUser(),$request,null);
            }
            catch(OAuth2\OAuth2ServerException $e)
            {
                return $e->getHttpResponse();
            }
        }

        return $this->container->get('templating')->renderResponse('NSApiBundle:Authorize:authorize.html.twig', array(
            'form' => $form->createView(),
            'client' => $client,
        ));
    }
}