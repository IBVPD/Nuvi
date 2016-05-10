<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class IsolateViable
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class IsolateViable extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\IsolateViable';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IsolateViable';
    }
}
