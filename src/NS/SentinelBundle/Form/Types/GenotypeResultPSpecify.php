<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of GenotypeResultPSpecify
 *
 */
class GenotypeResultPSpecify extends TranslatableArrayChoice
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
