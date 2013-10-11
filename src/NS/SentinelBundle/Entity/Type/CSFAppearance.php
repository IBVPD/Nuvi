<?php

namespace NS\SentinelBundle\Entity\Type;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class CSFAppearance extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Type\CSFAppearance';

    public function getName()
    {
        return 'CSFAppearance';
    }
    
}
