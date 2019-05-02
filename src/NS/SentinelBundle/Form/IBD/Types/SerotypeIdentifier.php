<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of SerotypeIdentifier
 *
 */
class SerotypeIdentifier extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const CONVENTIONAL = 1;
    public const REALTIME     = 2;
    public const QUELLUNG     = 3;
    public const OTHER        = 4;

    protected $values = [
        self::CONVENTIONAL => 'Conventional multiplex PCR',
        self::REALTIME => 'Realtime multiplex PCR',
        self::QUELLUNG => 'Quellung',
        self::OTHER => 'Other',
    ];
}
