<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class GramStain extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const NO_ORGANISM_DETECTED = 0;
    public const GM_POSITIVE          = 1;
    public const GM_NEGATIVE          = 2;
    public const GM_VARIABLE          = 3;
    public const UNKNOWN              = 99;

    protected $values = [
        self::NO_ORGANISM_DETECTED => 'No Organism Detected',
        self::GM_POSITIVE => 'Gram-positive organism',
        self::GM_NEGATIVE => 'Gram-negative organism',
        self::GM_VARIABLE => 'Gram-variable',
        self::UNKNOWN => 'Unknown',
    ];
}
