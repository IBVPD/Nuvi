<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class PCVType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        PCV10 = 1,
        PCV13 = 2;

    protected $values = [
        self::PCV10 => 'PCV10',
        self::PCV13 => 'PCV13',
    ];
}
