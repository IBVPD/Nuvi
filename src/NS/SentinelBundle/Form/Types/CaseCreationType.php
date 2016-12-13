<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 09/05/16
 * Time: 3:01 PM
 */

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

class CaseCreationType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const BASE = 1;
    const SITE = 2;
    const RRL  = 3;
    const NL   = 4;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    protected $values = [
        self::BASE => 'Case',
        self::SITE => 'Site Lab',
        self::RRL  => 'RRL',
        self::NL   => 'NL',
    ];

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
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
            'choices'     => $this->values,
            'placeholder' => 'Please Select...',
        ]);

        $resolver->setDefined(['special_values']);
        $resolver->addAllowedTypes(['special_values'=>'array']);
    }

    /**
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function setAuthChecker(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     *
     * @param string $routeBase
     * @return string
     */
    public function getRoute($routeBase)
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
