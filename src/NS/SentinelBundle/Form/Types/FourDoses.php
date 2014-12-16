<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class FourDoses extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const ONE     = 1;
    const TWO     = 2;
    const THREE   = 3;
    const FOUR    = 4;
    const UNKNOWN = 99;

    protected $values = array(
                            self::ONE     => "1 dose",
                            self::TWO     => "2 doses",
                            self::THREE   => "3 doses",
                            self::FOUR    => "≥ 4 doses",
                            self::UNKNOWN => 'Unknown',
                            );
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'FourDoses';
    }

}