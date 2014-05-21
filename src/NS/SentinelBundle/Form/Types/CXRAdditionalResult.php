<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of CXRAdditionalResult
 *
 */
class CXRAdditionalResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const CONSOLIDATION     = 1;
    const PLEURAL_EFFUSION  = 2;
    const AIR_BRONCHOGRAM   = 3;
    const INFILTRATE        = 4;
    const UNKNOWN           = 99;

    protected $values = array(
                                self::CONSOLIDATION     => 'Consolidation',
                                self::PLEURAL_EFFUSION  => 'Pleural effusion',
                                self::AIR_BRONCHOGRAM   => 'Air bronchogram',
                                self::INFILTRATE        => 'Interstitial infiltrate',
                                self::UNKNOWN           => 'Unknown',
                             );

    public function getName()
    {
        return 'CXRAdditionalResult';
    }
}
