<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of GenotypeResultP
 *
 */
class GenotypeResultP extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const P1 = 1;
    const P2 = 2;
    const P3 = 3;
    const P4 = 4;
    const P5 = 5;
    const P6 = 6;
    const P7 = 7;
    const P8 = 8;
    const P9 = 9;
    const P10 = 10;
    const P11 = 11;
    const P12 = 12;
    const P14 = 14;
    const P19 = 19;
    const P25 = 25;
    const P28 = 28;
    const NON_TYPEABLE = 30;
    const MIXED = 40;
    const OTHER = 50;

    protected $values = array(
                                self::P1 => 'P[1]',
                                self::P2 => 'P[2]',
                                self::P3 => 'P[3]',
                                self::P4 => 'P[4]',
                                self::P5 => 'P[5]',
                                self::P6 => 'P[6]',
                                self::P7 => 'P[7]',
                                self::P8 => 'P[8]',
                                self::P9 => 'P[9]',
                                self::P10 => 'P[10]',
                                self::P11 => 'P[11]',
                                self::P12 => 'P[12]',
                                self::P14 => 'P[14]',
                                self::P19 => 'P[19]',
                                self::P25 => 'P[25]',
                                self::P28 => 'P[28]',
                                self::NON_TYPEABLE => 'Non-typeable',
                                self::MIXED => 'Mixed (specify)',
                                self::OTHER => 'Other (specify)',
                             );

    public function getName()
    {
        return 'GenotypeResultP';
    }
}
