<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of CXRResult
 *
 */
class CXRResult extends TranslatableArrayChoice
{
    const FIRST_VALUE = 1;

    protected $values = array(
                                self::FIRST_VALUE => 'First Value',
                             );

    public function getName()
    {
        return 'CXRResult';
    }
}
