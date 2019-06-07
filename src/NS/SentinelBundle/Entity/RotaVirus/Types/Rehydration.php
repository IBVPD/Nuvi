<?php

namespace NS\SentinelBundle\Entity\RotaVirus\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;
use NS\SentinelBundle\Form\RotaVirus\Types\Rehydration as BaseType;

class Rehydration extends ArrayChoice
{
    /** @var string */
    protected $convert_class = BaseType::class;

    public function getName(): string
    {
        return 'Rehydration';
    }
}
