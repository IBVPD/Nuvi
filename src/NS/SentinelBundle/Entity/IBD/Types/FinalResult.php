<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class FinalResult
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class FinalResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\FinalResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'FinalResult';
    }
}
