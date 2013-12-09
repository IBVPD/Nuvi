<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Doses extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Types\Doses';

    public function getName()
    {
        return 'Doses';
    }
    
}
