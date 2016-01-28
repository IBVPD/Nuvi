<?php

namespace NS\SentinelBundle\Locale;

use \Symfony\Component\HttpKernel\Event\GetResponseEvent;
use \Symfony\Component\HttpKernel\KernelEvents;
use \Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use \Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use \Symfony\Component\Security\Core\SecurityContextInterface;
use \Symfony\Component\Security\Core\Event\AuthenticationEvent;
use \Symfony\Component\Security\Http\Event\SwitchUserEvent;

/**
 * Class Login
 * @package NS\SentinelBundle\Locale
 */
class Login implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var
     */
    private $session;

    /**
     * Login constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param string $defaultLocale
     */
    public function __construct(TokenStorageInterface $tokenStorage, $defaultLocale = 'en')
    {
        $this->tokenStorage = $tokenStorage;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $this->session = $request->getSession();

        if (!$request->hasPreviousSession() || !$this->session->isStarted()) {
            return;
        }

        // try to see if the locale has been set as a _locale session parameter
        try {
            $locale = $this->session->get('_locale', $request->attributes->get('_locale'));
            $token = $this->tokenStorage->getToken();

            if ($locale) {
                $request->setLocale($locale);
            } elseif ($token) {
                $user = $token->getUser();
                if ($user->getLanguage()) {
                    $locale = $user->getLanguage();
                    $this->session->set('_locale', $locale);
                    $request->setLocale($locale);
                }
            } else {
                $request->setLocale($this->session->get('_locale', $this->defaultLocale));
            }
        } catch (AuthenticationCredentialsNotFoundException $e) {
            $request->setLocale($this->session->get('_locale', $this->defaultLocale));
        }
    }

    /**
     * @param AuthenticationEvent $event
     */
    public function onLogin(AuthenticationEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user || !$this->session || !$this->session->isStarted()) {
            return;
        }

        if (!$this->session->get('_locale', false)) {
            $locale = $this->findLocale($user);
            if ($locale) {
                $this->session->set('_locale', $locale);
            }
        }
    }

    /**
     * @param $user
     * @return null
     */
    public function findLocale($user)
    {
        if (method_exists($user, 'getLanguage') && $user->getLanguage()) {
            return $user->getLanguage();
        }

        return null;
    }

    /**
     * @param SwitchUserEvent $event
     */
    public function switchUser(SwitchUserEvent $event)
    {
        $user = $event->getTargetUser();

        $event->getRequest()->getSession()->set('_locale', $user->getLanguage());
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // must be registered before the default Locale listener
        return array(KernelEvents::REQUEST => array(array('onKernelRequest', 17)));
    }
}
