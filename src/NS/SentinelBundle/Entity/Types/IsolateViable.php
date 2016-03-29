<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class IsolateViable
 * @package NS\SentinelBundle\Entity\Types
 */
class IsolateViable extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\IsolateViable';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IsolateViable';
    }
}
