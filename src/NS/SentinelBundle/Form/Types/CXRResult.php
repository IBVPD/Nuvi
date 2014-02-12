<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class CXRResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NORMAL       = 0;
    const CONSISTENT   = 1;
    const INCONCLUSIVE = 2;
    const OTHER        = 3;
    const UNKNOWN      = 99;

    protected $values = array(
                            self::NORMAL       => 'Normal', 
                            self::CONSISTENT   => 'Consistent with Pneumonia', 
                            self::INCONCLUSIVE => 'Inconclusive',
                            self::OTHER        => 'Other',
                            self::UNKNOWN => 'Unknown');
    
    public function getName()
    {
        return 'CXRResult';
    }
}