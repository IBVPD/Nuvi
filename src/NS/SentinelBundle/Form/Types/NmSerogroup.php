<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of NmSerogroup
 *
 */
class NmSerogroup extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const A     = 1;
    const B     = 2;
    const B_E   = 3;
    const C     = 4;
    const W     = 5;
    const X     = 6;
    const Y     = 7;
    const Y_W   = 8;
    const Z     = 9;
    const NON_BY_PCR  = 10;//Non-groupable (NG) by PCR (negative for Serogroups A, B, C, W, X, and Y)
    const NON_BY_SASG = 11;//Non-groupable (NG) by SASG
    const NOT_DONE = 12;//Not done
    const OTHER = 99;//Other (specify):

    protected $values = array(
                                self::A     => 'A',
                                self::B     => 'B',
                                self::B_E   => 'B/E. coli K1 (result from LA)',
                                self::C     => 'C',
                                self::W     => 'W',
                                self::X     => 'X',
                                self::Y     => 'Y',
                                self::Y_W   => 'Y/W (result from LA)',
                                self::Z     => 'Z',
                                self::NON_BY_PCR  => 'Non-groupable (NG) by PCR (negative for Serogroups A, B, C, W, X, and Y)',
                                self::NON_BY_SASG => 'Non-groupable (NG) by SASG',
                                self::NOT_DONE => 'Not done',
                                self::OTHER => 'Other',
                             );

    public function getName()
    {
        return 'NmSerogroup';
    }
}
