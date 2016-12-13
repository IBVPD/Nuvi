<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of HiSerotype
 *
 */
class HiSerotype extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const A                    = 1;
    const B                    = 2;
    const C                    = 3;
    const D                    = 4;
    const E                    = 5;
    const F                    = 6;
    const NON_TYPE_B           = 7;
    const NON_TYPEABLE_BY_PCR  = 8;
    const NON_TYPEABLE_BY_SAST = 9;
    const NOT_DONE             = 10;
    const OTHER                = 99;

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
