<?php

namespace NS\SentinelBundle\Listeners;

use Gedmo\Loggable\Loggable;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Description of DoctrineExtensionListener
 *
 * @author gnat
 */
class DoctrineExtensionListener
{

    private $securityContext;
    private $gedmoLoggable;

    /**
     * 
     * @param SecurityContextInterface $securityContext
     * @param Loggable $loggable
     */
    public function __construct(SecurityContextInterface $securityContext, \Gedmo\Mapping\MappedEventSubscriber $loggable)
    {
        $this->securityContext = $securityContext;
        $this->gedmoLoggable   = $loggable;
    }

    /**
     * 
     * @param GetResponseEvent $event
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (null !== $this->securityContext->getToken() && $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->gedmoLoggable->setUsername($this->securityContext->getToken()->getUsername());
        }
    }

}
