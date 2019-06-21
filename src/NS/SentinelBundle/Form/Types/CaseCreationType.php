<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CaseCreationType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const BASE = 1;
    public const SITE = 2;
    public const RRL  = 3;
    public const NL   = 4;

    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    protected $values = [
        self::BASE => 'Case',
        self::SITE => 'Site Lab',
        self::RRL  => 'RRL',
        self::NL   => 'NL',
    ];

    public function configureOptions(OptionsResolver $resolver): void
    {
        if ($this->authChecker->isGranted('ROLE_CAN_CREATE')) {
            $values = [];
            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_CASE')) {
                $values[self::BASE] = $this->values[self::BASE];
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_LAB')) {
                $values[self::SITE] = $this->values[self::SITE];
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_RRL_LAB')) {
                $values[self::RRL] = $this->values[self::RRL];
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_NL_LAB')) {
                $values[self::NL] = $this->values[self::NL];
            }

            $this->values = $values;
        } else {
            $this->values = [];
        }

        $resolver->setDefaults([
            'choices'     => array_flip($this->values),
            'placeholder' => 'Please Select...',
//            'translation_domain' => 'messages',
        ]);

        $resolver->setDefined(['special_values']);
        $resolver->addAllowedTypes('special_values','array');
    }

    public function setAuthChecker(AuthorizationCheckerInterface $authChecker): void
    {
        $this->authChecker = $authChecker;
    }

    public function getRoute(string $routeBase): ?string
    {
        switch ($this->getValue()) {
            case self::BASE:
                return $routeBase . 'Edit';
            case self::SITE:
                return $routeBase . 'LabEdit';
            case self::RRL:
                return $routeBase . 'RRLEdit';
            case self::NL:
                return $routeBase . 'NLEdit';
            default:
                return $routeBase . 'Index';
        }
    }
}
