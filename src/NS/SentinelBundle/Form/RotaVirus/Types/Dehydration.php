<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of Dehydration
 *
 */
class Dehydration extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NONE    = 0;
    const SEVERE  = 1;
    const SOME    = 2;
    const MODERATE = 3;
    const UNKNOWN = 99;

    protected $values = [
        self::NONE => 'None',
        self::SEVERE => 'Severe',
        self::MODERATE => 'Moderate',
        self::SOME => 'Some',
        self::UNKNOWN => 'Unknown',
    ];
}
