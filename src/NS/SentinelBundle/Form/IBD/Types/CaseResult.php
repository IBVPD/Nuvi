<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of IBDCaseResult
 *
 */
class CaseResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const UNKNOWN   = 0;
    const SUSPECTED = 1;
    const PROBABLE  = 2;
    const CONFIRMED = 3;

    protected $values = [
                                self::UNKNOWN   => 'Unknown',
                                self::SUSPECTED => 'Suspected',
                                self::PROBABLE  => 'Probable',
                                self::CONFIRMED => 'Confirmed',
    ];
}
