<?php

namespace NS\SentinelBundle\Entity\RotaVirus\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class GenotypeResultG
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class GenotypeResultG extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG';

    /**
     * @return string
     */
    public function getName()
    {
        return 'GenotypeResultG';
    }
}
