<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class Rehydration
 * @package NS\SentinelBundle\Entity\Types
 */
class Rehydration extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\Rehydration';

    /**
     * @return string
     */
    public function getName()
    {
        return 'Rehydration';
    }
}
