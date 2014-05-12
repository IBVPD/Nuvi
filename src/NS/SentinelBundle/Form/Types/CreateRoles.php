<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of CreateRoles
 *
 * @author gnat
 */
class CreateRoles extends TranslatableArrayChoice
{
    const BASE = 1;
    const SITE = 2;
    const RRL  = 3;
    const NL   = 4;

    private $securityContext;

    protected $values = array(
                                self::BASE  => 'Case',
                                self::SITE  => 'Site Lab',
                                self::RRL   => 'RRL',
                                self::NL    => 'NL',
                             );

    // Form AbstractType functions
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        if($this->securityContext->getToken())
        {
            $user = $this->securityContext->getToken()->getUser();
            if($user->getCanCreate())
            {
                $values = array();
                if($user->getCanCreateCases())
                    $values[self::BASE] = $this->values[self::BASE];

                if($user->getCanCreateLabs())
                    $values[self::SITE] = $this->values[self::SITE];

                if($user->getCanCreateRRLLabs())
                    $values[self::RRL] = $this->values[self::RRL];

                if($user->getCanCreateNLLabs())
                    $values[self::NL] = $this->values[self::NL];

                $this->values = $values;
            }
        }

        $resolver->setDefaults(array(
            'choices'     => $this->values,
            'empty_value' => 'Please Select...',
        ));

        $resolver->setOptional(array('special_values'));

        $resolver->addAllowedTypes(array('special_values'=>'array'));
    }

    public function setSecurityContext(SecurityContextInterface $sc)
    {
        $this->securityContext = $sc;
    }

    public function getName()
    {
        return 'CreateRoles';
    }
}
