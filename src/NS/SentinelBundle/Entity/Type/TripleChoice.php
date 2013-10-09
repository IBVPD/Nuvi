<?php

namespace NS\SentinelBundle\Entity\Type;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class TripleChoice extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Type\TripleChoice';

    public function getName()
    {
        return 'TripleChoice';
    }
    
}
