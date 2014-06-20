<?php

namespace NS\SentinelBundle\Services;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Description of LoginListener
 *
 * @author gnat
 */
class LoginListener
{
    protected $router;
    protected $security;
    protected $dispatcher;

    public function __construct(RouterInterface $router, SecurityContextInterface $security, EventDispatcherInterface $dispatcher)
    {
        $this->router     = $router;
        $this->security   = $security;
        $this->dispatcher = $dispatcher;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        if('apiLoginCheck' != $event->getRequest()->get('_route'))
            $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $user = $this->security->getToken()->getUser();
        
        if($user->isOnlyAdmin())
            $response = new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
        else if($user->isOnlyApi())
            $response = new RedirectResponse($this->router->generate('ns_api_dashboard'));
        else
            $response = new RedirectResponse($this->router->generate('home_redirect'));

        $event->setResponse($response);
    }
}
