<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of CaseStatus
 *
 */
class CaseStatus extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const OPEN      = 0;
    const COMPLETE  = 1;
    const CANCELLED = 2;
    const DELETED   = 3;

    protected $values = [
        self::OPEN => 'Open',
        self::COMPLETE => 'Complete',
        self::CANCELLED => 'Cancelled',
        self::DELETED => 'Deleted',
    ];
}
