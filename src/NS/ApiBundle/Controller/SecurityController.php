<?php

namespace NS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of SecurityController
 *
 * @author gnat
 */
class SecurityController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/login",name="apiLogin")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR))
        {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        else
            $error = '';

        if ($error)
            $error = $error->getMessage(); // WARNING! Symfony source code identifies this line as a potential security threat.

        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        return $this->render('NSApiBundle:Security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/login_check",name="apiLoginCheck")
     */
    public function loginCheckAction(Request $request)
    {

    }
}
