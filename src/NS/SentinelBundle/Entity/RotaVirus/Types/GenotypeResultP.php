<?php

namespace NS\SentinelBundle\Entity\RotaVirus\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class GenotypeResultP
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class GenotypeResultP extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP';

    /**
     * @return string
     */
    public function getName()
    {
        return 'GenotypeResultP';
    }
}
