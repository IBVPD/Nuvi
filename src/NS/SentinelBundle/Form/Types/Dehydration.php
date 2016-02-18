<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of Dehydration
 *
 */
class Dehydration extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NONE    = 0;
    const SEVERE  = 1;
    const SOME    = 2;
    const UNKNOWN = 99;

    protected $values = array(
                                self::NONE      => 'None',
                                self::SEVERE    => 'Severe',
                                self::SOME      => 'Some',
                                self::UNKNOWN   => 'Unknown',
                             );
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Dehydration';
    }
}
