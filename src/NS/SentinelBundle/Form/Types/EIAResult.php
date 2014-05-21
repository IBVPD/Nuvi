<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of EIAResult
 *
 */
class EIAResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const POSITIVE      = 1;
    const NEGATIVE      = 2;
    const INDETERMINED  = 3;
    const NOT_DONE      = 9;

    protected $values = array(
                                self::POSITIVE     => 'Positive',
                                self::NEGATIVE     => 'Negative',
                                self::INDETERMINED => 'Indetermined',
                                self::NOT_DONE     => 'Not Done',
                             );

    public function getName()
    {
        return 'EIAResult';
    }
}
