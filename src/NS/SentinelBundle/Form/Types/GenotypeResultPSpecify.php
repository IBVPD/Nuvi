<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of GenotypeResultPSpecify
 *
 */
class GenotypeResultPSpecify extends ArrayChoice
{
    const FIRST_VALUE = 1;

    protected $values = array(
                                self::FIRST_VALUE => 'First Value',
                             );

    public function getName()
    {
        return 'GenotypeResultPSpecify';
    }
}
