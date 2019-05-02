<?php

namespace NS\SentinelBundle\Form\Pneumonia\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableSetChoice;

/**
 * Description of CXRAdditionalResult
 *
 */
class CXRAdditionalResult extends TranslatableSetChoice implements TranslationContainerInterface
{
    public const CONSOLIDATION     = 1;
    public const PLEURAL_EFFUSION  = 2;
    public const AIR_BRONCHOGRAM   = 3;
    public const INFILTRATE        = 4;
    public const ATELECTASIS       = 5;
    public const INCONCLUSIVE      = 6;
    public const OTHER             = 7;
    public const UNKNOWN           = 99;

    protected $set = [
        self::CONSOLIDATION => 'Consolidation',
        self::ATELECTASIS => 'Atelectasis',
        self::PLEURAL_EFFUSION => 'Pleural effusion',
        self::AIR_BRONCHOGRAM => 'Air bronchogram',
        self::INFILTRATE => 'Interstitial infiltrate',
        self::INCONCLUSIVE => 'Inconclusive',
        self::OTHER => 'Other',
        self::UNKNOWN => 'Unknown',
    ];


    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        return $this->set;
    }
}
