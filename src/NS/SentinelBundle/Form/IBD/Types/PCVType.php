<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of PCVType
 *
 */
class PCVType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const PCV10 = 1;
    public const PCV13 = 2;

    protected $values = [
        self::PCV10 => 'PCV10',
        self::PCV13 => 'PCV13',
    ];
}
