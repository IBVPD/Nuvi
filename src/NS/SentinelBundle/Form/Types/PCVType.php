<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of PCVType
 *
 */
class PCVType extends ArrayChoice
{
    const PCV10 = 1;
    const PCV13 = 2;

    protected $values = array(
                                self::PCV10 => 'PCV10',
                                self::PCV13 => 'PCV13',
                             );

    public function getName()
    {
        return 'PCVType';
    }
}
