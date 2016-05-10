<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class IsolateType
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class IsolateType extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\IsolateType';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IsolateType';
    }
}
