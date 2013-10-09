<?php

namespace NS\SentinelBundle\Form\Type;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Diagnosis extends ArrayChoice
{
    const MENINGITIS = 1;
    const PNEUMONIA  = 2;
    const SEPSIS     = 3;
    const OTHER      = 4;
    const UNKNOWN    = 99;

    protected $values = array(
                            self::MENINGITIS  => 'Meningitis', 
                            self::PNEUMONIA   => 'Pneumonia', 
                            self::SEPSIS      => 'Sepsis',
                            self::OTHER       => 'Other',
                            self::UNKNOWN     => 'Unknown');
    
    public function getName()
    {
        return 'Doses';
    }
}