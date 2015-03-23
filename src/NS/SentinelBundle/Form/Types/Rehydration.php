<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of Rehydration
 *
 */
class Rehydration extends TranslatableArrayChoice implements TranslationContainerInterface
{

    const ORAL     = 1;
    const IV       = 2;
    const OTHER    = 3;
    const BOTH     = 4;
    const MULTIPLE = 5;
    const UNKNOWN  = 99;

    protected $values = array(
        self::ORAL     => 'Oral - ORS/ORT',
        self::IV       => 'IV fluids',
        self::OTHER    => 'Other',
        self::BOTH     => 'Both (ORS/ORT and IV fluids)',
        self::MULTIPLE => 'ORS/ORT and/or IV fluids and/or Other/Multiple',
        self::UNKNOWN  => 'Unknown',
    );

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Rehydration';
    }

}
