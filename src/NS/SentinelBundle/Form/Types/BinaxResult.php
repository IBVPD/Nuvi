<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of BinaxResult
 *
 */
class BinaxResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NEGATIVE     = 0;
    const POSITIVE     = 1;
    const INCONCLUSIVE = 2;
    const UNKNOWN      = 99;

    protected $values = array(
                                self::NEGATIVE      => 'Negative',
                                self::POSITIVE      => 'Positive',
                                self::INCONCLUSIVE  => 'Inconclusive',
                                self::UNKNOWN       => 'Unknown',
                             );

    public function getName()
    {
        return 'BinaxResult';
    }
}
