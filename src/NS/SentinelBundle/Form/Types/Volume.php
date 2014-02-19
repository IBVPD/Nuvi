<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of Volume
 *
 */
class Volume extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const YES = 1;
    const NO  = 2;

    protected $values = array(
                                self::YES => '≥200µl',
                                self::NO  => '<200µl',
                             );

    public function getName()
    {
        return 'Volume';
    }
}
