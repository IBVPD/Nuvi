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
    const ORAL    = 1;
    const IV      = 2;
    const OTHER   = 3;
    const UNKNOWN = 99;

    protected $values = array(
                                self::ORAL      => 'Oral',
                                self::IV        => 'IV',
                                self::OTHER     => 'Other',
                                self::UNKNOWN   => 'Unknown',
                             );

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Rehydration';
    }
}
