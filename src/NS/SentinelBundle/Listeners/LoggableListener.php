<?php

namespace NS\SentinelBundle\Listeners;

use \Gedmo\Loggable\Loggable;
use \Gedmo\Mapping\MappedEventSubscriber;
use \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Description of LoggableListener
 *
 * @author gnat
 */
class LoggableListener
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var Loggable|\Gedmo\Mapping\MappedEventSubscriber
     */
    private $gedmoLoggable;

    /**
     * LoggableListener constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authChecker
     * @param MappedEventSubscriber $loggable
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker, MappedEventSubscriber $loggable)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker  = $authChecker;
        $this->gedmoLoggable = $loggable;
    }

    /**
     * 
     */
    public function onKernelRequest()
    {
        if (null !== $this->tokenStorage->getToken() && $this->authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->gedmoLoggable->setUsername($this->tokenStorage->getToken()->getUsername());
        }
    }
}
