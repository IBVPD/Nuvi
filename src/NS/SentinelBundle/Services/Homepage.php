<?php

namespace NS\SentinelBundle\Services;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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
    /**
     * @var
     */
    private $tokenStorage;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Homepage constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param RouterInterface $router
     */
    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    /**
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function getHomepageResponse(Request $request)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user->isOnlyAdmin()) {
            return new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
        }

        $locale = $request->attributes->get('_locale', $request->getLocale());
        $route  = $this->router->generate('homepage', array('_locale' => $locale));

        return new RedirectResponse($route);
    }
}
