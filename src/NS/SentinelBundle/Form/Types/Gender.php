<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Gender extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NO_SELECTION   = 0;

    const MALE   = 1;
    const FEMALE = 2;

    protected $values = array(
                            self::NO_SELECTION => 'N/A',
                            self::MALE         => "Male",
                            self::FEMALE       => "Female",
                             );
    
    public function getName()
    {
        return 'Gender';
    }

}