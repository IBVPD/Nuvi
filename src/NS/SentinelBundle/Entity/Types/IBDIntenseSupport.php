<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class IBDIntenseSupport
 * @package NS\SentinelBundle\Entity\Types
 */
class IBDIntenseSupport extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\IBDIntenseSupport';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IBDIntenseSupport';
    }
}
