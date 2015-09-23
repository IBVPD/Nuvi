<?php

namespace NS\SentinelBundle\Entity\Types;
use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class IBDCreateRoles
 * @package NS\SentinelBundle\Entity\Types
 */
class IBDCreateRoles extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\IBDCreateRoles';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IBDCreateRoles';
    }   
}

