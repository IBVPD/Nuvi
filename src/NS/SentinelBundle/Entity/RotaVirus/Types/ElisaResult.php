<?php

namespace NS\SentinelBundle\Entity\RotaVirus\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class ElisaResult
 * @package NS\SentinelBundle\Entity\RotaVirus\Types
 */
class ElisaResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'ElisaResult';
    }
}
