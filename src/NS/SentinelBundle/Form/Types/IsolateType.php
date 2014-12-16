<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of IsolateType
 *
 */
class IsolateType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const SPN   = 1;
    const HI    = 2;
    const NM    = 3;
    const OTHER = 4;

    protected $values = array(
                                self::SPN   => 'Spn',
                                self::HI    => 'Hi',
                                self::NM    => 'Nm',
                                self::OTHER => 'Other',
                             );

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'IsolateType';
    }
}
