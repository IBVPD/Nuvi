<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of GenotypeResultP
 *
 */
class GenotypeResultP extends ArrayChoice
{
    const FIRST_VALUE = 1;

    protected $values = array(
                                self::FIRST_VALUE => 'First Value',
                             );

    public function getName()
    {
        return 'GenotypeResultP';
    }
}
