<?php

namespace NS\SentinelBundle\Services;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Description of LoginListener
 *
 * @author gnat
 */
class LoginListener
{
    protected $homepage;
    protected $dispatcher;

    /**
     *
     * @param \NS\SentinelBundle\Services\Homepage $homepage
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(Homepage $homepage, EventDispatcherInterface $dispatcher)
    {
        $this->homepage   = $homepage;
        $this->dispatcher = $dispatcher;
    }

    /**
     *
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        if ('apiLoginCheck' != $event->getRequest()->get('_route')) {
            $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
        }
    }

    /**
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $this->homepage->getHomepageResponse($event->getRequest());
        $event->setResponse($response);
    }
}
