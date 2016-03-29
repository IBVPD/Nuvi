<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class IBDCaseResult
 * @package NS\SentinelBundle\Entity\Types
 */
class IBDCaseResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\IBDCaseResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IBDCaseResult';
    }
}
