<?php

namespace NS\SentinelBundle\Entity\Meningitis\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class CSFAppearance extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = '\NS\SentinelBundle\Form\Meningitis\Types\CSFAppearance';

    /**
     * @return string
     */
    public function getName()
    {
        return 'CSFAppearance';
    }
}
