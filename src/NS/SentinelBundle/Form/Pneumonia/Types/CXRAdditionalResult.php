<?php

namespace NS\SentinelBundle\Form\Pneumonia\Types;

use NS\UtilBundle\Form\Types\TranslatableSetChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

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
    const UNKNOWN           = 99;

    protected $set = [
        self::CONSOLIDATION => 'Consolidation',
        self::PLEURAL_EFFUSION => 'Pleural effusion',
        self::AIR_BRONCHOGRAM => 'Air bronchogram',
        self::INFILTRATE => 'Interstitial infiltrate',
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
