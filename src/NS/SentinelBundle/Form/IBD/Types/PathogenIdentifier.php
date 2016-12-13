<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of PathogenIdentifier
 *
 */
class PathogenIdentifier extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const CONVENTIONAL = 1;
    const REALTIME     = 2;
    const OTHER        = 3;

    protected $values = [
                                self::CONVENTIONAL => 'Conventional PCR',
                                self::REALTIME     => 'Realtime PCR',
                                self::OTHER        => 'Other',
    ];
}
