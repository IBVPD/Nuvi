<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class NmSerogroup
 * @package NS\SentinelBundle\Entity\Types
 */
class NmSerogroup extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\NmSerogroup';

    /**
     * @return string
     */
    public function getName()
    {
        return 'NmSerogroup';
    }   
}

