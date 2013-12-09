<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Gender extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Types\Gender';

    public function getName()
    {
        return 'Gender';
    }
    
}
