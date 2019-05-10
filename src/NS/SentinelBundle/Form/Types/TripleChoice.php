<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class TripleChoice extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const NO      = 0;
    public const YES     = 1;
    public const UNKNOWN = 99;

    protected $values = [
        self::NO => 'No',
        self::YES => 'Yes',
        self::UNKNOWN => 'Unknown'
    ];
}
