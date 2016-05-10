<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Diagnosis extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = '\NS\SentinelBundle\Form\IBD\Types\Diagnosis';

    /**
     * @return string
     */
    public function getName()
    {
        return 'Diagnosis';
    }
}
