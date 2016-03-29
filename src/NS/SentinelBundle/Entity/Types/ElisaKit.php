<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class ElisaKit
 * @package NS\SentinelBundle\Entity\Types
 */
class ElisaKit extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\ElisaKit';

    /**
     * @return string
     */
    public function getName()
    {
        return 'ElisaKit';
    }
}
