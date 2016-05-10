<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class IBDCaseResult
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class CaseResult extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\CaseResult';

    /**
     * @return string
     */
    public function getName()
    {
        return 'CaseResult';
    }
}
