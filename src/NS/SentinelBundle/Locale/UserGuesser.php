<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 30/04/16
 * Time: 10:07 AM
 */

namespace NS\SentinelBundle\Locale;

use Lunetics\LocaleBundle\LocaleGuesser\LocaleGuesserInterface;
use Lunetics\LocaleBundle\Validator\MetaValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class UserGuesser implements LocaleGuesserInterface
{
    /**
     * @var bool
     */
    private $identifiedLocale = false;

    /**
     * @var MetaValidator
     */
    private $metaValidator;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthorizationChecker
     */
    private $authChecker;

    /**
     * UserGuesser constructor.
     * @param MetaValidator $metaValidator
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationChecker $authorizationChecker
     */
    public function __construct(MetaValidator $metaValidator, TokenStorageInterface $tokenStorage, AuthorizationChecker $authorizationChecker)
    {
        $this->metaValidator = $metaValidator;
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authorizationChecker;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function guessLocale(Request $request)
    {
        try {
            if ($this->authChecker->isGranted('IS_FULLY_AUTHENTICATED') || $this->authChecker->isGranted('ROLE_CAN_CHANGE_LANGUAGE')) {
                return false;
            }
        } catch (AuthenticationCredentialsNotFoundException $exception) {
            return false;
        }


        if ($this->authChecker->isGranted('ROLE_COUNTRY_LEVEL') || $this->authChecker->isGranted('ROLE_SITE_LEVEL')) {
            $user = $this->tokenStorage->getToken()->getUser();
            $foundLocale = null;

            if ($user->getLanguage()) {
                $foundLocale = $user->getLanguage();
            }

            if ($this->metaValidator->isAllowed($foundLocale)) {
                $this->identifiedLocale = $foundLocale;
                return $this->identifiedLocale;
            }
        }


        return false;
    }

    /**
     * @return mixed
     */
    public function getIdentifiedLocale()
    {
        return $this->identifiedLocale;
    }
}
