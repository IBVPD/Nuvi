<?php

namespace NS\SentinelBundle\Services;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Description of Homepage
 *
 * @author gnat
 */
class Homepage
{
    private $security;

    private $router;

    /**
     *
     * @param SecurityContextInterface $security
     * @param RouterInterface $router
     */
    public function __construct(SecurityContextInterface $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router   = $router;
    }

    /**
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function getHomepageResponse(Request $request)
    {
        $user = $this->security->getToken()->getUser();

        if ($user->isOnlyAdmin())
        {
            return new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
        }
        else if ($user->isOnlyApi())
        {
            return new RedirectResponse($this->router->generate('ns_api_dashboard'));
        }

        $locale = $request->attributes->get('_locale', $request->getLocale());
        $route  = $this->router->generate('homepage', array('_locale' => $locale));

        return new RedirectResponse($route);
    }
}