<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class CSFAppearance extends ArrayChoice
{
    protected $convert_class = '\NS\SentinelBundle\Form\Types\CSFAppearance';

    public function getName()
    {
        return 'CSFAppearance';
    }
    
}
