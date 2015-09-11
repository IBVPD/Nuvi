<?php

namespace NS\SentinelBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="admin_login")
     * @Method(methods={"GET"})
     */
    public function loginAction(Request $request)
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('NSSentinelBundle:Security:login.html.twig', array(
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
        ));
    }

    /**
     * @Route("/switchLanguage", name="switchLangugae")
     * @Method(methods={"GET"})
     */
    public function switchLanguageAction(Request $request)
    {
        $session = $request->getSession();
        $currentLocale = $session->get('_locale');
        $locale = ($currentLocale == 'en') ? 'fr' : 'en';

        $session->set('_locale', $locale);
        return $this->redirect($this->generateUrl('user_dashboard', array('_locale' => $locale)));
    }

    /**
     * @Route("/{_locale}",name="homepage")
     * @Method(methods={"GET"})
     */
    public function homepageAction(Request $request)
    {
        $repo = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD");
        $byCountry = $repo->getByCountry();
        $bySite = $repo->getBySite();
        $byDiagnosis = $repo->getByDiagnosis();
        $form = $this->createForm('IBDFieldPopulationFilterType', null, array('site_type' => 'advanced'));
        $report = $this->get('ns.sentinel.services.report');
        $cResult = $report->getCulturePositive($request, $form, 'homepage');

        return $this->render('NSSentinelBundle:Security:homepage.html.twig', array(
            'byCountry' => $byCountry,
            'bySite' => $bySite,
            'byDiagnosis' => $byDiagnosis,
            'cResult' => $cResult['results']));
    }

    /**
     * @Route("/",name="homepage_redirect")
     * @Method(methods={"GET"})
     */
    public function homepageRedirectAction(Request $request)
    {
        return $this->get('ns.sentinel.services.homepage')->getHomepageResponse($request);
    }
}
