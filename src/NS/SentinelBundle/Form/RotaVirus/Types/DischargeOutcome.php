<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of DischargeOutcome
 *
 */
class DischargeOutcome extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const DISCHARGED_ALIVE     = 1;
    public const DIED                 = 2;
    public const TRANSFERRED          = 3;
    public const LEFT_AGAINST_ADVICE  = 4;
    public const UNKNOWN              = 99;

    protected $values = [
        self::DISCHARGED_ALIVE      => 'Discharged alive',
        self::DIED                  => 'Died',
        self::TRANSFERRED           => 'Transferred',
        self::LEFT_AGAINST_ADVICE   => 'Left/Discharged against medical advice',
        self::UNKNOWN               => 'Unknown',
    ];
}
