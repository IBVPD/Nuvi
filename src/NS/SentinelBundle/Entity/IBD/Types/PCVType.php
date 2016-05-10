<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class PCVType
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class PCVType extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\PCVType';

    /**
     * @return string
     */
    public function getName()
    {
        return 'PCVType';
    }
}
