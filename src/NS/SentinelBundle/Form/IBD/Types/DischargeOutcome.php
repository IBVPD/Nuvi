<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of DischargeOutcome
 *
 */
class DischargeOutcome extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const DISCHARGED_ALIVE_WITHOUT_SEQUELAE = 1;
    const DISCHARGED_ALIVE_WITH_SEQUELAE    = 2;
    const DIED                              = 3;
    const TRANSFERRED                       = 4;
    const LEFT_AGAINST_ADVICE               = 5;
    const UNKNOWN                           = 99;

    protected $values = array(
                                self::DISCHARGED_ALIVE_WITHOUT_SEQUELAE => 'Discharged alive, without sequelae',
                                self::DISCHARGED_ALIVE_WITH_SEQUELAE    => 'Discharged alive, with sequelae',
                                self::DIED                              => 'Died',
                                self::TRANSFERRED                       => 'Transferred',
                                self::LEFT_AGAINST_ADVICE               => 'Left/Discharged against medical advice',
                                self::UNKNOWN                           => 'Unknown',
                             );
}
