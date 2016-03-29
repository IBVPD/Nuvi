<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class CSFAppearance extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const CLEAR          = 1;
    const TURBID         = 2;
    const BLOODY         = 3;
    const XANTHROCHROMIC = 4;
    const OTHER          = 5;
    const NOT_ASSESSED   = 6;
    const UNKNOWN        = 99;

    protected $values = array(
                            self::CLEAR => 'Clear',
                            self::TURBID =>'Turbid/Cloudy',
                            self::BLOODY => 'Bloody',
                            self::XANTHROCHROMIC => 'Xanthrochromic',
                            self::OTHER => 'Other',
                            self::NOT_ASSESSED => 'Not assessed',
                            self::UNKNOWN => 'Unknown');
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'CSFAppearance';
    }
}
