<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class HiSerotype extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const 
        A                    = 1,
        B                    = 2,
        C                    = 3,
        D                    = 4,
        E                    = 5,
        F                    = 6,
        NON_TYPE_B           = 7,
        NON_TYPEABLE_BY_PCR  = 8,
        NON_TYPEABLE_BY_SAST = 9,
        NOT_DONE             = 10,
        OTHER                = 99;

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
