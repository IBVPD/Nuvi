<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class DischargeOutcome extends ArrayChoice
{
    const NO_SELECTION   = -1;

    const NA          = 0;
    const DISCHARGED  = 1;
    const DIED        = 2;
    const TRANSFERRED = 3;
    const LEFT        = 4;
    const UNKNOWN     = 99;

    protected $values = array(
                            self::NA          => 'N/A',
                            self::DISCHARGED  => 'No', 
                            self::DIED        => 'Yes', 
                            self::TRANSFERRED => 'Transferred',
                            self::LEFT        => 'Left Against Medical Advice',
                            self::UNKNOWN     => 'Unknown');
    
    public function getName()
    {
        return 'DischargeOutcome';
    }
}