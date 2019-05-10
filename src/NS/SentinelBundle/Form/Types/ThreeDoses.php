<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of ThreeDoses
 *
 */
class ThreeDoses extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const ONE     = 1;
    public const TWO     = 2;
    public const THREE   = 3;
    public const UNKNOWN = 99;

    protected $values = [
        self::ONE => "1 dose",
        self::TWO => "2 doses",
        self::THREE => "≥ 3 doses",
        self::UNKNOWN => 'Unknown',
    ];
}
