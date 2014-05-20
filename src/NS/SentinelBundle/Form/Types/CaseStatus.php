<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of CaseStatus
 *
 */
class CaseStatus extends TranslatableArrayChoice
{
    const OPEN      = 0;
    const COMPLETE  = 1;
    const CANCELLED = 2;
    const DELETED   = 3;

    protected $values = array(
                                self::OPEN      => 'Open',
                                self::COMPLETE  => 'Complete',
                                self::CANCELLED => 'Cancelled',
                                self::DELETED   => 'Deleted',
                             );

    public function getName()
    {
        return 'CaseStatus';
    }
}
