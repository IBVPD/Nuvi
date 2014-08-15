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
    const MALE    = 1;
    const FEMALE  = 2;
    const UNKNOWN = 99;

    protected $values = array(
                            self::MALE    => "Male",
                            self::FEMALE  => "Female",
                            self::UNKNOWN => 'Unknown',
                             );

    public function __construct($value = null)
    {
        if($value == 'M')
            $value = 'Male';
        else if($value == 'F')
            $value = 'Female';

        parent::__construct($value);
    }

    public function getName()
    {
        return 'Gender';
    }
}