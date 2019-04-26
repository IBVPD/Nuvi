<?php

namespace NS\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of SecurityController
 *
 * @author gnat
 * @Route("/oauth/v2/auth")
 */
class SecurityController extends Controller
{
    /**
     * @return Response
     * @Route("/login",name="apiLogin")
     * @Method(methods={"GET"})
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('NSApiBundle:Security:login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/login_check",name="apiLoginCheck")
     * @Method(methods={"POST"})
     */
    public function loginCheckAction()
    {
    }
}
