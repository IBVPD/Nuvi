<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class BinaxResult
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class BinaxResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\BinaxResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'BinaxResult';
    }
}
