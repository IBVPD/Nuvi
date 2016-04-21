<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of CreateRoles
 *
 * @author gnat
 */
class CreateRoles extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const BASE = 1;
    const SITE = 2;
    const RRL  = 3;
    const NL   = 4;

    /**
     * @var
     */
    private $authChecker;

    protected $values = array(
        self::BASE => 'Case',
        self::SITE => 'Site Lab',
        self::RRL  => 'RRL',
        self::NL   => 'NL',
    );

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        if ($this->authChecker->isGranted('ROLE_CAN_CREATE')) {
            $values = array();
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
            $this->values = array();
        }

        $resolver->setDefaults(array(
            'choices'     => $this->values,
            'placeholder' => 'Please Select...',
        ));

        $resolver->setDefined(array('special_values'));
        $resolver->addAllowedTypes(array('special_values'=>'array'));
    }

    /**
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function setAuthChecker(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'CreateRoles';
    }

    /**
     *
     * @param string $routeBase
     * @return string
     */
    public function getRoute($routeBase)
    {
        switch ($this->getValue()) {
            case CreateRoles::BASE:
                return $routeBase . 'Edit';
            case CreateRoles::SITE:
                return $routeBase . 'LabEdit';
            case CreateRoles::RRL:
                return $routeBase . 'RRLEdit';
            case CreateRoles::NL:
                return $routeBase . 'NLEdit';
            default:
                return $routeBase . 'Index';
        }
    }
}
