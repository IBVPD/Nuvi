<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class SerotypeIdentifier extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const 
        CONVENTIONAL = 1,
        REALTIME     = 2,
        QUELLUNG     = 3,
        OTHER        = 4;

    protected $values = [
        self::CONVENTIONAL => 'Conventional multiplex PCR',
        self::REALTIME => 'Realtime multiplex PCR',
        self::QUELLUNG => 'Quellung',
        self::OTHER => 'Other',
    ];
}
