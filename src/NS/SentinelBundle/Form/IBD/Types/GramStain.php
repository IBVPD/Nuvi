<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of GramStain
 *
 */
class GramStain extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NO_ORGANISM_DETECTED = 0;
    const GM_POSITIVE          = 1;
    const GM_NEGATIVE          = 2;
    const UNKNOWN              = 99;

    protected $values = [
        self::NO_ORGANISM_DETECTED => 'No Organism Detected',
        self::GM_POSITIVE => 'Gram-positive organism',
        self::GM_NEGATIVE => 'Gram-negative organism',
        self::UNKNOWN => 'Unknown',
    ];
}
