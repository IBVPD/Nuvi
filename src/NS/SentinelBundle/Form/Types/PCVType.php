<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of PCVType
 *
 */
class PCVType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const PCV10 = 1;
    const PCV13 = 2;

    protected $values = array(
                                self::PCV10 => 'PCV10',
                                self::PCV13 => 'PCV13',
                             );

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'PCVType';
    }
}
