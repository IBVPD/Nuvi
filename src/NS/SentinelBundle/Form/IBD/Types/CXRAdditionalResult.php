<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableSetChoice;

/**
 * Description of CXRAdditionalResult
 *
 */
class CXRAdditionalResult extends TranslatableSetChoice implements TranslationContainerInterface
{
    const CONSOLIDATION     = 1;
    const PLEURAL_EFFUSION  = 2;
    const AIR_BRONCHOGRAM   = 3;
    const INFILTRATE        = 4;
    const ATELECTASIS       = 5;
    const INCONCLUSIVE      = 6;
    const OTHER             = 7;
    const UNKNOWN           = 99;

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
