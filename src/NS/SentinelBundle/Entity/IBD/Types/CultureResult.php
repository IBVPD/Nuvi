<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class CultureResult
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class CultureResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\CultureResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'CultureResult';
    }
}
