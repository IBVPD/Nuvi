<?php

namespace NS\SentinelBundle\Form\Pneumonia\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class CXRResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const 
        NORMAL       = 0,
        CONSISTENT   = 1,
        VIRAL_PNEUMONIA = 10,
        VIRAL_BACTERIAL = 11,
        INCONCLUSIVE = 2,
        OTHER        = 3,
        UNKNOWN      = 99;
 
    protected $values = [
        self::NORMAL => 'Normal',
        self::CONSISTENT => 'Consistent with Bacterial Pneumonia',
        self::VIRAL_PNEUMONIA => 'Consistent with Viral Pneumonia',
        self::VIRAL_BACTERIAL => 'Consistent with Viral/Bacterial Co-infection',
        self::INCONCLUSIVE => 'Inconclusive',
        self::OTHER => 'Other',
        self::UNKNOWN => 'Unknown'
    ];
}
