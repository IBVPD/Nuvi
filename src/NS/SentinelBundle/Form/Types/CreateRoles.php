<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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

    private $securityContext;

    protected $values = array(
        self::BASE => 'Case',
        self::SITE => 'Site Lab',
        self::RRL  => 'RRL',
        self::NL   => 'NL',
    );

    // Form AbstractType functions
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        if ($this->securityContext->isGranted('ROLE_CAN_CREATE'))
        {
            $values = array();
            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_CASE'))
                $values[self::BASE] = $this->values[self::BASE];

            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_LAB'))
                $values[self::SITE] = $this->values[self::SITE];

            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_RRL_LAB'))
                $values[self::RRL] = $this->values[self::RRL];

            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_NL_LAB'))
                $values[self::NL] = $this->values[self::NL];

            $this->values = $values;
        }

        $resolver->setDefaults(array(
            'choices'     => $this->values,
            'empty_value' => 'Please Select...',
        ));

        $resolver->setOptional(array('special_values'));

        $resolver->addAllowedTypes(array('special_values'=>'array'));
    }

    /**
     *
     * @param SecurityContextInterface $securityContext
     */
    public function setSecurityContext(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
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
        switch ($this->getValue())
        {
            case CreateRoles::BASE:
                return $routeBase . 'Edit';
            case CreateRoles::SITE:
                return $routeBase . 'Edit';
            case CreateRoles::RRL:
                return $routeBase . 'RRLEdit';
            case CreateRoles::NL:
                return $routeBase . 'NLEdit';
            case CreateRoles::LAB:
                return $routeBase . 'LabEdit';
            default:
                return $routeBase . 'Index';
        }
    }

}
