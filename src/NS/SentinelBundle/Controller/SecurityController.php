<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class SecurityController extends Controller
{    
    /**
     * @Route("/login", name="admin_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                );
    }

    /**
     * @Route("/switchLanguage", name="switchLangugae")
     * @Template()
     */
    public function switchLanguageAction(Request $request)
    {
        $session       = $request->getSession();
        $currentLocale = $session->get('_locale');
        $locale        = ($currentLocale == 'en')?'fr':'en';

        $session->set('_locale', $locale);
        return $this->redirect($this->generateUrl('user_dashboard',array('_locale'=>$locale)));
    }
    
    /**
     * @Route("/{_locale}",name="homepage")
     */
    public function homepageAction(Request $request)
    {
        $sc = $this->get('security.context');
        
        if($sc->isGranted('ROLE_REGION'))
            return $this->forward ("NSSentinelBundle:User:regionDashboard",array('_route'=>'homepage'));
        if($sc->isGranted('ROLE_COUNTRY'))
            return $this->forward ("NSSentinelBundle:User:countryDashboard",array('_route'=>'homepage'));
        if($sc->isGranted('ROLE_SITE'))
            return $this->forward ("NSSentinelBundle:User:siteDashboard",array('_route'=>'homepage'));
        if($sc->isGranted('ROLE_LAB')||$sc->isGranted('ROLE_RRL_LAB'))
            return $this->forward ("NSSentinelBundle:User:labDashboard",array('_route'=>'homepage'));
        if($sc->isGranted('ROLE_ADMIN'))
            return $this->redirect ($this->generateUrl ('sonata_admin_dashboard'));

        throw new UnauthorizedHttpException(null, "You have no roles!");
    }
}
