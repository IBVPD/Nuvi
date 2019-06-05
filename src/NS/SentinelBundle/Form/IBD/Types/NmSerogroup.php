<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class NmSerogroup extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const 
        A           = 1,
        B           = 2,
        B_E         = 3,
        C           = 4,
        W           = 5,
        X           = 6,
        Y           = 7,
        Y_W         = 8,
        Z           = 9,
        NON_BY_PCR  = 10,
        NON_BY_SASG = 11,
        NOT_DONE    = 12,
        _29E        = 13;

    public const OTHER       = 99;

    protected $values = [
        self::A           => 'A',
        self::B           => 'B',
        self::B_E         => 'B/E. coli K1 (result from LA)',
        self::_29E        => '29E',
        self::C           => 'C',
        self::W           => 'W',
        self::X           => 'X',
        self::Y           => 'Y',
        self::Y_W         => 'Y/W (result from LA)',
        self::Z           => 'Z',
        self::NON_BY_PCR  => 'Non-groupable (NG) by PCR (negative for Serogroups A, B, C, W, X, and Y)',
        self::NON_BY_SASG => 'Non-groupable (NG) by SASG',
        self::NOT_DONE    => 'Not done',
        self::OTHER       => 'Other',
    ];
}
