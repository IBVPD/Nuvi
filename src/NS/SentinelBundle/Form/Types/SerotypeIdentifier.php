<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of SerotypeIdentifier
 *
 */
class SerotypeIdentifier extends TranslatableArrayChoice implements TranslationContainerInterface
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
