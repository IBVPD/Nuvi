<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of SpnSerotype
 *
 */
class SpnSerotype extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const _1                   = 1;
    const _2                   = 2;
    const _3                   = 3;
    const _4                   = 4;
    const _5                   = 5;
    const _8                   = 6;
    const _13                  = 7;
    const _14                  = 8;
    const _20                  = 9;
    const _21                  = 10;
    const _31                  = 11;
    const _34                  = 12;
    const _38                  = 13;
    const _39                  = 14;
    const _45                  = 15;
    const _46                  = 16;
    const _10A                 = 17;
    const _10F                 = 18;
    const _11A                 = 19;
    const _11A_VARIANT         = 20;
    const _12A                 = 21;
    const _12F                 = 22;
    const _12F_VARIANT         = 23;
    const _15A                 = 24;
    const _15B                 = 25;
    const _15B_VARIANT         = 26;
    const _15C                 = 27;
    const _16F                 = 28;
    const _17F                 = 29;
    const _18A                 = 30;
    const _18C                 = 31;
    const _19A                 = 32;
    const _19F                 = 33;
    const _22F                 = 34;
    const _22F_VARIANT         = 35;
    const _23A                 = 36;
    const _23B                 = 37;
    const _23F                 = 38;
    const _24A                 = 39;
    const _25F                 = 41;
    const _33F                 = 42;
    const _33F_VARIANT         = 43;
    const _35A                 = 44;
    const _35B                 = 45;
    const _35F                 = 46;
    const _6A                  = 47;
    const _6A_VARIANT          = 48;
    const _6B                  = 49;
    const _6C                  = 50;
    const _6C_VARIANT          = 51;
    const _7C                  = 52;
    const _7F                  = 53;
    const _7F_VARIANT          = 54;
    const _9A                  = 55;
    const _9N                  = 56;
    const _9N_VARIANT          = 57;
    const _9V                  = 58;
    const _9V_VARIANT          = 59;
    const _HIGH_LYTA_VALUE     = 90;
    const _NON_PCV_13_SEROTYPE = 91;
    const _NOT_DONE            = 92;
    const OTHER                = 99;

    protected $values = [
        self::_1                   => '1',
        self::_2                   => '2',
        self::_3                   => '3',
        self::_4                   => '4',
        self::_5                   => '5',
        self::_8                   => '8',
        self::_13                  => '13',
        self::_14                  => '14',
        self::_20                  => '20',
        self::_21                  => '21',
        self::_31                  => '31',
        self::_34                  => '34',
        self::_38                  => '38',
        self::_39                  => '39',
        self::_45                  => '45',
        self::_46                  => '46',
        self::_10A                 => '10A',
        self::_10F                 => '10F/10C/33C',
        self::_11A                 => '11A',
        self::_11A                 => '11A/11D',
        self::_12A                 => '12A',
        self::_12F                 => '12F',
        self::_12F_VARIANT         => '12F/12A/12B/44/46',
        self::_15A                 => '15A/15F',
        self::_15B                 => '15B',
        self::_15B_VARIANT         => '15B/15C',
        self::_15C                 => '15C',
        self::_16F                 => '16F',
        self::_17F                 => '17F',
        self::_18A                 => '18A/18B/18C/18F',
        self::_18C                 => '18C',
        self::_19A                 => '19A',
        self::_19F                 => '19F',
        self::_22F                 => '22F',
        self::_22F_VARIANT         => '22F/22A',
        self::_23A                 => '23A',
        self::_23B                 => '23B',
        self::_23F                 => '23F',
        self::_24A                 => '24A/24B/24F',
        self::_25F                 => '25F/25A/38',
        self::_33F                 => '33F',
        self::_33F_VARIANT         => '33F/33A/37',
        self::_35A                 => '35A/35C/42',
        self::_35B                 => '35B',
        self::_35F                 => '35F/47F',
        self::_6A                  => '6A',
        self::_6A_VARIANT          => '6A/6B',
        self::_6B                  => '6B',
        self::_6C                  => '6C/6D',
        self::_6C_VARIANT          => '7C/7B/40',
        self::_7C                  => '7C',
        self::_7F                  => '7F',
        self::_7F_VARIANT          => '7F/7A',
        self::_9A                  => '9A',
        self::_9N                  => '9N',
        self::_9N_VARIANT          => '9N/9L',
        self::_9V                  => '9V',
        self::_9V_VARIANT          => '9V/9A',
        self::_HIGH_LYTA_VALUE     => 'Spn Not able to be serotyped because of high lytA Ct value',
        self::_NON_PCV_13_SEROTYPE => 'Non PCV 13 serotype**',
        self::_NOT_DONE            => 'Not done',
        self::OTHER                => 'Other',
    ];
}
