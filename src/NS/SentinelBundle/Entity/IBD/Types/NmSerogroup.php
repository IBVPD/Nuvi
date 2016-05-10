<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class NmSerogroup
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class NmSerogroup extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\NmSerogroup';

    /**
     * @return string
     */
    public function getName()
    {
        return 'NmSerogroup';
    }
}
