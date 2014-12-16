<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of ElisaResult
 *
 */
class ElisaResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NEGATIVE = 0;
    const POSITIVE = 1;
    const INDETERMINATE = 2;
    const UNKNOWN = 99;

    protected $values = array(
                                self::NEGATIVE => 'Negative',
                                self::POSITIVE => 'Positive',
                                self::INDETERMINATE => 'Indeterminate',
                                self::UNKNOWN => 'Unknown',
                             );
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ElisaResult';
    }
}
