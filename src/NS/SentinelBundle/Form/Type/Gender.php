<?php

namespace NS\SentinelBundle\Form\Type;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Gender extends ArrayChoice
{
    const NO_SELECTION   = 0;

    const MALE   = 1;
    const FEMALE = 2;

    protected $values = array(
                            self::NO_SELECTION => 'N/A',
                            self::MALE         => "Male",
                            self::FEMALE       => "Female",
                             );
    
    public function getName()
    {
        return 'Gender';
    }

}