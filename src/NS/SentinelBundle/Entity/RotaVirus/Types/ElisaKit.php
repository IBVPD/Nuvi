<?php

namespace NS\SentinelBundle\Entity\RotaVirus\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class ElisaKit
 * @package NS\SentinelBundle\Entity\RotaVirus\Types
 */
class ElisaKit extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit';

    /**
     * @return string
     */
    public function getName()
    {
        return 'ElisaKit';
    }
}
