<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class CaseStatus
 * @package NS\SentinelBundle\Entity\Types
 */
class CaseStatus extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\CaseStatus';

    /**
     * @return string
     */
    public function getName()
    {
        return 'CaseStatus';
    }
}
