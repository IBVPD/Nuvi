<?php

namespace NS\SentinelBundle\Form\Pneumonia\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableSetChoice;

class CXRAdditionalResult extends TranslatableSetChoice implements TranslationContainerInterface
{
    public const 
        CONSOLIDATION     = 1,
        PLEURAL_EFFUSION  = 2,
        AIR_BRONCHOGRAM   = 3,
        INFILTRATE        = 4,
        ATELECTASIS       = 5,
        INCONCLUSIVE      = 6,
        OTHER             = 7,
        UNKNOWN           = 99;

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
