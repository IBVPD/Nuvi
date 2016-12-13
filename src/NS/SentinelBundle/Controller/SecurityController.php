<?php

namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Filter\Type\IBD\ReportFilterType;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="admin_login")
     * @Method(methods={"GET"})
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('NSSentinelBundle:Security:login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/switchLanguage", name="switchLangugae")
     * @Method(methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function switchLanguageAction(Request $request)
    {
        $session = $request->getSession();
        $currentLocale = $session->get('_locale');
        $locale = ($currentLocale == 'en') ? 'fr' : 'en';

        $session->set('_locale', $locale);
        return $this->redirect($this->generateUrl('homepage', ['_locale' => $locale]));
    }

    /**
     * @Route("/{_locale}",name="homepage")
     * @Method(methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepageAction(Request $request)
    {
        $repo = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD");
        $byCountry = $repo->getByCountry();
        $bySite = $repo->getBySite();
        $byDiagnosis = $repo->getByDiagnosis();
        $form = $this->createForm(ReportFilterType::class, null, ['site_type' => 'advanced']);
        $report = $this->get('ns_sentinel.ibd_report');
        $cResult = $report->getCulturePositive($request, $form, 'homepage');

        return $this->render('NSSentinelBundle:Security:homepage.html.twig', [
            'byCountry' => $byCountry,
            'bySite' => $bySite,
            'byDiagnosis' => $byDiagnosis,
            'cResult' => $cResult['results']]);
    }

    /**
     * @Route("/",name="homepage_redirect")
     * @Method(methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function homepageRedirectAction(Request $request)
    {
        return $this->get('ns.sentinel.services.homepage')->getHomepageResponse($request);
    }
}
