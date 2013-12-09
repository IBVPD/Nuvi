<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class TripleChoice extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Types\TripleChoice';

    public function getName()
    {
        return 'TripleChoice';
    }
    
}
