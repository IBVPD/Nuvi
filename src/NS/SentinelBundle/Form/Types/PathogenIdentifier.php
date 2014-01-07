<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of PathogenIdentifier
 *
 */
class PathogenIdentifier extends ArrayChoice
{
    const CONVENTIONAL = 1;
    const REALTIME     = 2;
    const OTHER        = 3;

    protected $values = array(
                                self::CONVENTIONAL => 'Conventional PCR',
                                self::REALTIME     => 'Realtime PCR',
                                self::OTHER        => 'Other',
                             );

    public function getName()
    {
        return 'PathogenIdentifier';
    }
}
