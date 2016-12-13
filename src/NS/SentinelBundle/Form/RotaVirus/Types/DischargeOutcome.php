<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of DischargeOutcome
 *
 */
class DischargeOutcome extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const DISCHARGED_ALIVE     = 1;
    const DIED                 = 2;
    const TRANSFERRED          = 3;
    const LEFT_AGAINST_ADVICE  = 4;
    const UNKNOWN              = 99;

    protected $values = [
                                self::DISCHARGED_ALIVE      => 'Discharged alive',
                                self::DIED                  => 'Died',
                                self::TRANSFERRED           => 'Transferred',
                                self::LEFT_AGAINST_ADVICE   => 'Left/Discharged against medical advice',
                                self::UNKNOWN               => 'Unknown',
    ];
}
