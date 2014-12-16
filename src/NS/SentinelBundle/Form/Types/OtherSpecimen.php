<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of OtherSpecimen
 *
 */
class OtherSpecimen extends TranslatableArrayChoice
{
    const NONE    = 0;
    const PLEURAL = 1;
    const JOINT   = 2;
    const OTHER   = 3;

    protected $values = array(
                                self::NONE    => 'None',
                                self::PLEURAL => 'Pleural',
                                self::JOINT   => 'Joint',
                                self::OTHER   => 'Other',
                             );

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'OtherSpecimen';
    }
}
