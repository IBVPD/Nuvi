<?php

namespace NS\SentinelBundle\Form\Type;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class CSFAppearance extends ArrayChoice
{
    const NO_SELECTION   = 0;
    const CLEAR          = 1;
    const TURBID         = 2;
    const BLOODY         = 3;
    const XANTHROCHROMIC = 4;
    const OTHER          = 5;
    const NOT_ASSESSED   = 6;
    const UNKNOWN        = 99;

    protected $values = array(
                            self::NO_SELECTION => 'N/A',
                            self::CLEAR => 'Clear',
                            self::TURBID =>'Turbid/Cloudy',
                            self::BLOODY => 'Bloody',
                            self::XANTHROCHROMIC => 'Xanthrochromic',
                            self::OTHER => 'Other',
                            self::NOT_ASSESSED => 'Not assessed',
                            self::UNKNOWN => 'Unknown');
    
    public function getName()
    {
        return 'CSFAppearance';
    }
}