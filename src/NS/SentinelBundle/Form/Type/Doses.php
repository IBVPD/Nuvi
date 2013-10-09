<?php

namespace NS\SentinelBundle\Form\Type;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Doses extends ArrayChoice
{
    const ONE     = 1;
    const TWO     = 2;
    const THREE   = 3;
    const FOUR    = 4;
    const UNKNOWN = 99;

    protected $values = array(
                            self::ONE     => 1, 
                            self::TWO     => 2, 
                            self::THREE   => 3,
                            self::FOUR    => 4,
                            self::UNKNOWN => 'Unknown');
    
    public function getName()
    {
        return 'Doses';
    }
}