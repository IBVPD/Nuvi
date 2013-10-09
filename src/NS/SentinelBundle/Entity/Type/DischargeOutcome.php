<?php

namespace NS\SentinelBundle\Entity\Type;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class DischargeOutcome extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Type\Diagnosis';

    public function getName()
    {
        return 'Doses';
    }
    
}
