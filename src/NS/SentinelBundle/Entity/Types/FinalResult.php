<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class FinalResult
 * @package NS\SentinelBundle\Entity\Types
 */
class FinalResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\FinalResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'FinalResult';
    }
}

