<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of GAVIEligible
 *
 */
class GAVIEligible extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NEVER    = 1;
    const PREVIOUS = 2;
    const CURRENT  = 3;

    protected $values = array(
                                self::NEVER => 'Never',
                                self::PREVIOUS=>'Previous',
                                self::CURRENT=>'Current',
                             );

    public function getName()
    {
        return 'GAVIEligible';
    }
}
