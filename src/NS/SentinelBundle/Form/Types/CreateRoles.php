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
    const LAB  = 2;

    private $securityContext;

    protected $values = array(
                                self::BASE => 'Case',
                                self::LAB  => 'Lab',
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
                    $values[self::LAB] = $this->values[self::LAB];

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
