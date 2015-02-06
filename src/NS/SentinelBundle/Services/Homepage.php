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

        if($user->isOnlyAdmin())
            $response = new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
        else if($user->isOnlyApi())
            $response = new RedirectResponse($this->router->generate('ns_api_dashboard'));
        else
        {
            $_locale         = $request->attributes->get('_locale', $request->getLocale());
            $statusCode      = $request->attributes->get('statusCode', 301);
            $redirectToRoute = 'homepage';

            if ($this->router && $redirectToRoute)
                $response = new RedirectResponse($this->router->generate($redirectToRoute, array('_locale' => $_locale)), $statusCode);
            else
            {
                // TODO: this seems broken, as it will not handle if the site runs in a subdir
                // TODO: also it doesn't handle the locale at all and can therefore lead to an infinite redirect
                $response = new RedirectResponse($request->getScheme() . '://' . $request->getHttpHost() . '/', $statusCode);
            }
        }

        return $response;
    }
}
