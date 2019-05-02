<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of IBDCaseResult
 *
 */
class CaseResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const UNKNOWN   = 0;
    public const SUSPECTED = 1;
    public const PROBABLE  = 2;
    public const CONFIRMED = 3;

    protected $values = [
        self::UNKNOWN => 'Unknown',
        self::SUSPECTED => 'Suspected',
        self::PROBABLE => 'Probable',
        self::CONFIRMED => 'Confirmed',
    ];
}
