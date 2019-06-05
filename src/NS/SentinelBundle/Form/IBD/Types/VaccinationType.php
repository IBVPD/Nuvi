<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class VaccinationType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const 
        MEN_AFR_VAC     = 1,
        ACYW135_POLY    = 2,
        ACW135          = 3,
        ACYW135_CON     = 4,
        OTHER           = 5,
        B               = 6,
        C               = 7,
        UNKNOWN         = 99;

    protected $values = [
        self::MEN_AFR_VAC  => 'MenAfriVac (conjugate MenA)',
        self::ACYW135_POLY => 'ACYW135 (polysaccharide)',
        self::ACW135       => 'ACW135 (polysaccharide)',
        self::ACYW135_CON  => 'ACYW135 (conjugate)',
        self::B            => 'B recombinante',
        self::C            => 'C (conjugada)',
        self::OTHER        => 'Other',
        self::UNKNOWN      => 'Unknown',
    ];
}
