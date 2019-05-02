<?php

namespace NS\SentinelBundle\Form\Pneumonia\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of CXRResult
 *
 */
class CXRResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const NORMAL       = 0;
    public const CONSISTENT   = 1;
    public const VIRAL_PNEUMONIA = 10;
    public const VIRAL_BACTERIAL = 11;
    public const INCONCLUSIVE = 2;
    public const OTHER        = 3;
    public const UNKNOWN      = 99;
 
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
