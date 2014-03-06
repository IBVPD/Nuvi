<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Diagnosis extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Types\Diagnosis';

    public function getName()
    {
        return 'Diagnosis';
    }
    
}
