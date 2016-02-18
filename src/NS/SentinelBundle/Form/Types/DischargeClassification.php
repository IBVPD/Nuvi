<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of DischargeClassification
 *
 */
class DischargeClassification extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const CONFIRMED_HI      = 1;
    const CONFIRMED_SPN     = 2;
    const CONFIRMED_NM      = 3;
    const CONFIRMED_OTHER   = 4;
    const PROBABLE          = 5;
    const SUSPECT           = 6;
    const INCOMPLETE        = 7;
    const DISCARDED         = 8;
    const UNKNOWN           = 99;

    protected $values = array(
                            self::CONFIRMED_HI      => 'Lab-confirmed for HI',
                            self::CONFIRMED_SPN     => 'Lab-confirmed for Spn',
                            self::CONFIRMED_NM      => 'Lab-confirmed for Nm',
                            self::CONFIRMED_OTHER   => 'Lab-confirmed for other organism',
                            self::PROBABLE          => 'Probable',
                            self::SUSPECT           => 'Suspect',
                            self::INCOMPLETE        => 'Incomplete investigation',
                            self::DISCARDED         => 'Discarded case',
                            self::UNKNOWN           => 'Unknown',
                             );
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'DischargeClassification';
    }
}
