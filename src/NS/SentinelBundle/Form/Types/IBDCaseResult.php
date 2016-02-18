<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of IBDCaseResult
 *
 */
class IBDCaseResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const UNKNOWN   = 0;
    const SUSPECTED = 1;
    const PROBABLE  = 2;
    const CONFIRMED = 3;

    protected $values = array(
                                self::UNKNOWN   => 'Unknown',
                                self::SUSPECTED => 'Suspected',
                                self::PROBABLE  => 'Probable',
                                self::CONFIRMED => 'Confirmed',
                             );
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'IBDCaseResult';
    }
}
