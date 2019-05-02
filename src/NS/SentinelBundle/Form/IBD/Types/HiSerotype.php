<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of HiSerotype
 *
 */
class HiSerotype extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const A                    = 1;
    public const B                    = 2;
    public const C                    = 3;
    public const D                    = 4;
    public const E                    = 5;
    public const F                    = 6;
    public const NON_TYPE_B           = 7;
    public const NON_TYPEABLE_BY_PCR  = 8;
    public const NON_TYPEABLE_BY_SAST = 9;
    public const NOT_DONE             = 10;
    public const OTHER                = 99;

    protected $values = [
        self::A                    => 'a',
        self::B                    => 'b',
        self::C                    => 'c',
        self::D                    => 'd',
        self::E                    => 'e',
        self::F                    => 'f',
        self::NON_TYPE_B           => 'Non-type b',
        self::NON_TYPEABLE_BY_PCR  => 'Non-typeable (NT) by PCR (negative for serotypes a-f)',
        self::NON_TYPEABLE_BY_SAST => 'Non-typeable (NT) by SAST',
        self::NOT_DONE             => 'Not done',
        self::OTHER                => 'Other (specify)',
    ];
}
