<?php

namespace NS\SentinelBundle\Locale;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Login implements EventSubscriberInterface
{
    private $_defaultLocale;
    private $_securityContext;
    private $_session;
    private $_container;

    public function __construct(SecurityContextInterface $context, ContainerInterface $container, $defaultLocale = 'en')
    {
        $this->_defaultLocale   = $defaultLocale;
        $this->_securityContext = $context;
        $this->_container       = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request        = $event->getRequest();
        $this->_session = $request->getSession();

        if (!$request->hasPreviousSession() || !$this->_session->isStarted())
            return;

        // try to see if the locale has been set as a _locale session parameter
        try
        {
            $locale = $this->_session->get('_locale',$request->attributes->get('_locale'));
            if($locale)
                $request->setLocale($locale);
            else if($this->_securityContext->isGranted('IS_FULLY_AUTHENTICATED'))
            {
                $user = $this->_securityContext->getToken()->getUser();
                if($user->getLanguage())
                {
                    $locale = $user->getLanguage();
                    $this->_session->set('_locale',$locale);
                    $request->setLocale($locale);
                }
            }
            else
                $request->setLocale($this->_session->get('_locale', $this->_defaultLocale));
        }
        catch(AuthenticationCredentialsNotFoundException $e)
        {
            $request->setLocale($this->_session->get('_locale', $this->_defaultLocale));
        }
    }

    public function onLogin(AuthenticationEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if(!$user || !$this->_session || !$this->_session->isStarted())
            return;

        if(!$this->_session->get('_locale',false))
        {
            $locale = $this->findLocale($user);
            if($locale)
                $this->_session->set('_locale',$locale);
        }
    }

    public function findLocale($user)
    {
        if( method_exists($user, 'getLanguage') && $user->getLanguage())
            return $user->getLanguage();
//        else
//        {
//            $site = $this->_container->get('ns.sentinel.sites')->getSite(false);
//            if($site->getCountry()->getLanguage())
//                return $site->getCountry()->getLanguage();
//        }

        return null;
    }

    public function switchUser(SwitchUserEvent $event)
    {
        $user    = $event->getTargetUser();

        $event->getRequest()->getSession()->set('_locale',$user->getLanguage());
    }

    public static function getSubscribedEvents()
    {
        // must be registered before the default Locale listener
        return array( KernelEvents::REQUEST => array(array('onKernelRequest', 17)));
    }
}