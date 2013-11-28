<?php

namespace NS\SentinelBundle\Entity\Type;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Gender extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Type\Gender';

    public function getName()
    {
        return 'Gender';
    }
    
}
