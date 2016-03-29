<?php

namespace NS\SentinelBundle\Entity\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Class DischargeDiagnosis
 * @package NS\SentinelBundle\Entity\Types
 */
class DischargeDiagnosis extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = 'NS\SentinelBundle\Form\Types\DischargeDiagnosis';

    /**
     * @return string
     */
    public function getName()
    {
        return 'DischargeDiagnosis';
    }
}
