<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class IsolateType
 * @package NS\SentinelBundle\Entity\Types
 */
class IsolateType extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\IsolateType';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IsolateType';
    }   
}

