<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of IsolateType
 *
 */
class IsolateType extends ArrayChoice
{
    const SPN   = 1;
    const HI    = 2;
    const NM    = 3;
    const OTHER = 4;

    protected $values = array(
                                self::SPN   => 'Spn',
                                self::HI    => 'Hi',
                                self::NM    => 'Nm',
                                self::OTHER => 'Other',
                             );

    public function getName()
    {
        return 'isolatetype';
    }
}
