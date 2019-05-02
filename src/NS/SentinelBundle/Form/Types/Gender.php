<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Gender extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const MALE    = 1;
    public const FEMALE  = 2;
    public const UNKNOWN = 99;

    protected $values = [
        self::MALE => "Male",
        self::FEMALE => "Female",
        self::UNKNOWN => 'Unknown',
    ];

    /**
     *
     * @param string $value
     */
    public function __construct($value = null)
    {
        if ($value == 'M') {
            $value = 'Male';
        } elseif ($value == 'F') {
            $value = 'Female';
        }

        parent::__construct($value);
    }
}
