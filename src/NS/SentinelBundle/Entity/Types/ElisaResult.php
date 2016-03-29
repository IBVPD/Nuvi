<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class ElisaResult
 * @package NS\SentinelBundle\Entity\Types
 */
class ElisaResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\ElisaResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'ElisaResult';
    }
}
