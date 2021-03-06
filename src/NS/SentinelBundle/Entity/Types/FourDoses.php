<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class FourDoses extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = '\NS\SentinelBundle\Form\Types\FourDoses';

    /**
     * @return string
     */
    public function getName()
    {
        return 'FourDoses';
    }
}
