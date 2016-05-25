<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class TripleChoice extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NO      = 0;
    const YES     = 1;
    const UNKNOWN = 99;

    protected $values = array(
                            self::NO      => 'No',
                            self::YES     => 'Yes',
                            self::UNKNOWN => 'Unknown');
}
