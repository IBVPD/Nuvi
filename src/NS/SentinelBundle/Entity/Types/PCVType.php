<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class PCVType
 * @package NS\SentinelBundle\Entity\Types
 */
class PCVType extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\PCVType';

    /**
     * @return string
     */
    public function getName()
    {
        return 'PCVType';
    }
}
