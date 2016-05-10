<?php

namespace NS\SentinelBundle\Entity\RotaVirus\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class Dehydration
 * @package NS\SentinelBundle\Entity\RotaVirus\Types
 */
class Dehydration extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\RotaVirus\Types\Dehydration';

    /**
     * @return string
     */
    public function getName()
    {
        return 'Dehydration';
    }
}
