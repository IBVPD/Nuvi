<?php

namespace NS\SentinelBundle\Entity\Type;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class CXRResult extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Type\CXRResult';

    public function getName()
    {
        return 'CXRResult';
    }
    
}
