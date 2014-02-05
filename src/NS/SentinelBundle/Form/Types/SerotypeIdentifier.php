<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of SerotypeIdentifier
 *
 */
class SerotypeIdentifier extends ArrayChoice
{
    const CONVENTIONAL = 1;
    const REALTIME     = 2;
    const QUELLUNG     = 3;
    const OTHER        = 4;

    protected $values = array(
                                self::CONVENTIONAL => 'Conventional multiplex PCR',
                                self::REALTIME     => 'Realtime multiplex PCR',
                                self::QUELLUNG     => 'Quellung',
                                self::OTHER        => 'Other',
                             );

    public function getName()
    {
        return 'SerotypeIdentifier';
    }
}
