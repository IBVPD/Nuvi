<?php

namespace NS\SentinelBundle\Form\Type;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class CXRResult extends ArrayChoice
{
    const NORMAL       = 0;
    const CONSISTENT   = 1;
    const INCONCLUSIVE = 2;
    const OTHER        = 3;
    const UNKNOWN      = 99;

    protected $values = array(
                            self::NORMAL       => 'Normal', 
                            self::CONSISTENT   => 'Consistent with Pneumonia', 
                            self::INCONCLUSIVE => 'Inconclusive',
                            self::OTHER        => 'Other',
                            self::UNKNOWN => 'Unknown');
    
    public function getName()
    {
        return 'CXRResult';
    }
}