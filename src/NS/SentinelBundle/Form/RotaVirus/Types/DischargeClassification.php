<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of RotaVirusDischargeClassification
 *
 */
class DischargeClassification  extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const CONFIRMED  = 1;
    public const DISCARDED  = 2;
    public const INADEQUATE = 3;
    public const UNKNOWN    = 99;

    protected $values = [
        self::CONFIRMED => 'Confirmed',
        self::DISCARDED => 'Discarded',
        self::INADEQUATE => 'Inadequately Investigated',
        self::UNKNOWN   => 'Unknown',
    ];
}
