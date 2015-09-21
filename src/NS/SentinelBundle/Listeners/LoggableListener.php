<?php

namespace NS\SentinelBundle\Listeners;

use \Gedmo\Loggable\Loggable;
use \Gedmo\Mapping\MappedEventSubscriber;
use \Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Description of LoggableListener
 *
 * @author gnat
 */
class LoggableListener
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var Loggable|\Gedmo\Mapping\MappedEventSubscriber
     */
    private $gedmoLoggable;

    /**
     * 
     * @param SecurityContextInterface $securityContext
     * @param Loggable $loggable
     */
    public function __construct(SecurityContextInterface $securityContext, MappedEventSubscriber $loggable)
    {
        $this->securityContext = $securityContext;
        $this->gedmoLoggable   = $loggable;
    }

    /**
     * 
     */
    public function onKernelRequest()
    {
        if (null !== $this->securityContext->getToken() && $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->gedmoLoggable->setUsername($this->securityContext->getToken()->getUsername());
        }
    }
}
