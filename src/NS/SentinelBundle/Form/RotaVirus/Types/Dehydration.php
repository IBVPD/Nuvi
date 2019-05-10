<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of Dehydration
 *
 */
class Dehydration extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const NONE    = 0;
    public const SEVERE  = 1;
    public const SOME    = 2;
    public const MODERATE = 3;
    public const UNKNOWN = 99;

    protected $values = [
        self::NONE => 'None',
        self::SEVERE => 'Severe',
        self::MODERATE => 'Moderate',
        self::SOME => 'Some',
        self::UNKNOWN => 'Unknown',
    ];
}
