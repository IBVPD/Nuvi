<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class CultureResult
 * @package NS\SentinelBundle\Entity\Types
 */
class CultureResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\CultureResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'CultureResult';
    }   
}

