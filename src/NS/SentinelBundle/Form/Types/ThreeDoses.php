<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of ThreeDoses
 *
 */
class ThreeDoses extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const ONE     = 1;
    const TWO     = 2;
    const THREE   = 3;
    const UNKNOWN = 99;

    protected $values = array(
                            self::ONE     => "1 dose",
                            self::TWO     => "2 doses",
                            self::THREE   => "≥ 3 doses",
                            self::UNKNOWN => 'Unknown',
                             );

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ThreeDoses';
    }
}
