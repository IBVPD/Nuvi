<?php

namespace NS\SentinelBundle\Locale;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Doctrine\ORM\EntityManager;

class Login implements EventSubscriberInterface
{
    private $_defaultLocale;
    private $_securityContext;
    private $_session;

    public function __construct(SecurityContextInterface $context, $defaultLocale = 'en')
    {
        $this->_defaultLocale = $defaultLocale;
        $this->_securityContext = $context;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request        = $event->getRequest();
        $this->_session = $request->getSession();

        if (!$request->hasPreviousSession())
            return;

        // try to see if the locale has been set as a _locale session parameter
        try
        {
            $locale = $request->getSession()->get('_locale',$request->attributes->get('_locale'));
            if($locale)
                $request->setLocale($locale);
            else if($this->_securityContext->isGranted('IS_FULLY_AUTHENTICATED'))
            {
                $user = $this->_securityContext->getToken()->getUser();
                if($user->getLanguage())
                {
                    $locale = $user->getLanguage();
                    $request->getSession->set('_locale',$locale);
                    $request->setLocale($locale);
                }
            }
            else
                $request->setLocale($request->getSession()->get('_locale', $this->_defaultLocale));
        }
        catch(AuthenticationCredentialsNotFoundException $e)
        {
            $request->setLocale($request->getSession()->get('_locale', $this->_defaultLocale));
        }
    }

    public function onLogin(AuthenticationEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
    
        if(!$user || !$this->_session)
            return;

        if(!$this->_session->get('_locale',false) && method_exists($user, 'getLanguage'))
            $this->_session->set('_locale',$user->getLanguage());
    }

    public function switchUser(SwitchUserEvent $event)
    {
        $request = $event->getRequest();
        $user    = $event->getTargetUser();

        $request->getSession()->set('_locale',$user->getLanguage());
    }

    public static function getSubscribedEvents()
    {
        // must be registered before the default Locale listener
        return array( KernelEvents::REQUEST => array(array('onKernelRequest', 17)));
    }
}