<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of IsolateViable
 *
 */
class IsolateViable extends TranslatableArrayChoice
{
    const YES            = 1;
    const NO             = 2;
    const NOT_APPLICABLE = 3;

    protected $values = array(
        self::YES            => 'Yes',
        self::NO             => 'No',
        self::NOT_APPLICABLE => 'Not Applicable',
    );

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'IsolateViable';
    }
}
