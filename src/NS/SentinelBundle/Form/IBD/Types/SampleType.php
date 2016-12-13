<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of SampleType
 *
 */
class SampleType extends TranslatableArrayChoice implements TranslationContainerInterface
{

    const CSF         = 1;
    const ISOLATE     = 2;
    const WHOLE       = 3;
    const BROTH       = 4;
    const PLEURAL     = 5;
    const INOCULATED  = 6;
    const DNA         = 7;
    const CSF_ISOLATE = 8;

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
