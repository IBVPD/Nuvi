<?php

namespace NS\SentinelBundle\Form\Type;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class TripleChoice extends ArrayChoice
{
    const NO      = 0;
    const YES     = 1;
    const UNKNOWN = 99;

    protected $values = array(
                            self::NO      => 'No', 
                            self::YES     => 'Yes', 
                            self::UNKNOWN => 'Unknown');
    
    public function getName()
    {
        return 'TripleChoice';
    }
}