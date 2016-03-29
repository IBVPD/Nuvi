<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class Dehydration
 * @package NS\SentinelBundle\Entity\Types
 */
class Dehydration extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\Dehydration';

    /**
     * @return string
     */
    public function getName()
    {
        return 'Dehydration';
    }
}
