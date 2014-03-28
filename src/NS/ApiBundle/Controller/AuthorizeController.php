<?php

namespace NS\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
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
     */
    //@Route("/auth",name="apiAuth")
    public function authorizeAction(Request $request)
    {
        if (!$request->get('client_id')) 
            throw new NotFoundHttpException("Client id parameter {$request->get('client_id')} is missing.");

        $clientManager = $this->get('fos_oauth_server.client_manager.default');
        $client        = $clientManager->findClientByPublicId($request->get('client_id'));

        if (!($client instanceof Client))
            throw new NotFoundHttpException("Client {$request->get('client_id')} is not found.".  get_class($client));

        $authorize    = new Authorize();
        $form         = $this->createForm('api_oauth_server_authorize',$authorize);
        $oauthServier = $this->get('fos_oauth_server.server');

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

        return $this->render('NSApiBundle:Authorize:authorize.html.twig', array('form' => $form->createView(),'client' => $client));
    }
}