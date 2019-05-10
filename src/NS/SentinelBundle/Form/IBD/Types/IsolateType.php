<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of IsolateType
 *
 */
class IsolateType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const SPN   = 1;
    public const HI    = 2;
    public const NM    = 3;
    public const OTHER = 4;

    protected $values = [
        self::SPN => 'Spn',
        self::HI => 'Hi',
        self::NM => 'Nm',
        self::OTHER => 'Other',
    ];
}
