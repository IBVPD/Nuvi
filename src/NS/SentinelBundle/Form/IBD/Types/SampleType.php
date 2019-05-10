<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class SampleType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const CSF         = 1;
    public const ISOLATE     = 2;
    public const WHOLE       = 3;
    public const BROTH       = 4;
    public const PLEURAL     = 5;
    public const INOCULATED  = 6;
    public const DNA         = 7;
    public const CSF_ISOLATE = 8;

    protected $values = [
        self::CSF         => 'CSF',
        self::ISOLATE     => 'Isolate',
        self::WHOLE       => 'Whole Blood',
        self::BROTH       => 'Blood culture broth',
        self::PLEURAL     => 'Pleural Fluid',
        self::INOCULATED  => 'Inoculated',
        self::DNA         => 'DNA Extract',
        self::CSF_ISOLATE => 'CSF + Isolate',
    ];
}
