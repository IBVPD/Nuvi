<?php

namespace NS\SentinelBundle\Entity\RotaVirus\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class Rehydration
 * @package NS\SentinelBundle\Entity\RotaVirus\Types
 */
class Rehydration extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\RotaVirus\Types\Rehydration';

    /**
     * @return string
     */
    public function getName()
    {
        return 'Rehydration';
    }
}
