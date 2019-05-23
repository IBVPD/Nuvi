<?php

namespace NS\SentinelBundle\Repository\Pneumonia;

use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Repository\BaseLab;

class SiteLabRepository extends BaseLab
{
    protected $parentClass = Pneumonia::class;
}
