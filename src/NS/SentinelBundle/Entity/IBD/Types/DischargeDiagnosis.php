<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class DischargeDiagnosis
 * @package NS\SentinelBundle\Entity\IBD\Types
 */
class DischargeDiagnosis extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis';

    /**
     * @return string
     */
    public function getName()
    {
        return 'IBDDischargeDiagnosis';
    }
}
