<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class SpnSerotype extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const _1 = 1;
    public const _2 = 2;
    public const _3 = 3;
    public const _4 = 4;
    public const _5 = 5;
    public const _8 = 6;
    public const _13 = 7;
    public const _14 = 8;
    public const _20 = 9;
    public const _21 = 10;
    public const _31 = 11;
    public const _34 = 12;
    public const _38 = 13;
    public const _39 = 14;
    public const _45 = 15;
    public const _46 = 16;

    public const _6A = 47;
    public const _6A_VARIANT = 48;
    public const _6B = 49;
    public const _6C = 50;
    public const _7C_VARIANT = 51;

    public const _6D = 100;
    public const _6ABCD = 101;
    public const _6CD = 102;

    public const _7C = 52;
    public const _7F = 53;
    public const _7F_VARIANT = 54;
    public const _9A = 55;
    public const _9N = 56;
    public const _9N_VARIANT = 57;
    public const _9V = 58;
    public const _9V_VARIANT = 59;

    public const _7A = 103;
    public const _7B = 104;

    public const _9L = 107;

    public const _10B = 110;
    public const _10C = 111;
    public const _10FC33C = 112;

    public const _10A = 17;
    public const _10F = 18;
    public const _11A = 19;
    public const _11A_VARIANT = 20;

    public const _11B = 113;
    public const _11C = 114;
    public const _11D = 115;
    public const _11F = 116;

    public const _12A = 21;
    public const _12F = 22;
    public const _12F_VARIANT = 23;

    public const _12B = 118;

    public const _15A = 24;
    public const _15B = 25;
    public const _15B_VARIANT = 26;
    public const _15C = 27;

    public const _15F = 120;

    public const _16A = 123;
    public const _16F = 28;

    public const _17A = 124;

    public const _17F = 29;
    public const _18A = 127;
    public const _18C = 31;

    public const _18B = 125;
    public const _18F = 126;
    public const _18_ABCF = 30;

    public const _19A = 32;
    public const _19B = 128;
    public const _19C = 129;
    public const _19F = 33;
    public const _19BF = 130;
    public const _19F_VARIANT = 131;

    public const _22A = 132;
    public const _22F = 34;
    public const _22FA = 35;
    public const _23A = 36;
    public const _23B = 37;
    public const _23F = 38;
    public const _24A = 135;
    public const _24B = 133;
    public const _24F = 134;
    public const _24_ABF = 39;
    public const _25A = 136;
    public const _25F = 137;
    public const _25_FA_38 = 41;

    public const _28A = 138;
    public const _28F = 139;

    public const _32A = 140;
    public const _32F = 141;
    public const _33A = 142;
    public const _33B = 143;
    public const _33C = 144;
    public const _33D = 145;

    public const _33F = 42;
    public const _33F_VARIANT = 43;
    public const _35A = 44;
    public const _35B = 45;
    public const _35C = 147;
    public const _35F = 46;
    public const _35_AC_42 = 148;
    public const _35F_47F = 149;
    public const _38_25F_25A = 150;
    public const _41A = 151;
    public const _41F = 152;
    public const _47A = 153;
    public const _47F = 154;

    public const _HIGH_LYTA_VALUE = 90;
    public const _NON_PCV_13_SEROTYPE = 91;
    public const _NOT_DONE = 92;
    public const OTHER = 99;

    protected $values = [
        self::_1 => '1',
        self::_2 => '2',
        self::_3 => '3',
        self::_4 => '4',
        self::_5 => '5',
        self::_8 => '8',
        self::_13 => '13',
        self::_14 => '14',
        self::_20 => '20',
        self::_21 => '21',
        self::_31 => '31',
        self::_34 => '34',
        self::_38 => '38',
        self::_39 => '39',
        self::_45 => '45',
        self::_46 => '46',
        self::_6A => '6A',
        self::_6B => '6B',
        self::_6C => '6C', //'6C/6D'
        self::_6D => '6D',
        self::_6A_VARIANT => '6A/6B',

        self::_6ABCD => '6A/6B/6C/6D',
        self::_6CD => '6C/6D',

        self::_7C_VARIANT => '7C/7B/40',
        self::_7C => '7C',
        self::_7F => '7F',
        self::_7F_VARIANT => '7F/7A',

        self::_7A => '7A',
        self::_7B => '7B',

        self::_9A => '9A',
        self::_9L => '9L',
        self::_9N => '9N',
        self::_9N_VARIANT => '9N/9L',
        self::_9V => '9V',
        self::_9V_VARIANT => '9V/9A',

        self::_10A => '10A',
        self::_10B => '10B',
        self::_10C => '10C',
        self::_10F => '10F', // '10F/10C/33C'
        self::_10FC33C => '10F/10C/33C',

        self::_11A => '11A',

        self::_11B => '11B',
        self::_11C => '11C',
        self::_11D => '11D',
        self::_11F => '11F',
        self::_11A_VARIANT => '11A/11D',

        self::_12A => '12A',
        self::_12B => '12B',

        self::_12F => '12F',
        self::_12F_VARIANT => '12F/12A/12B/44/46',
        self::_15A => '15A/15F',
        self::_15B => '15B',
        self::_15B_VARIANT => '15B/15C',
        self::_15C => '15C',
        self::_15F => '15F',
        self::_16A => '16A',

        self::_16F => '16F',
        self::_17A => '17A',
        self::_17F => '17F',
        self::_18A => '18A', //'18A/18B/18C/18F'
        self::_18B => '18B',

        self::_18C => '18C',
        self::_18F => '18F',
        self::_18_ABCF => '18A/18B/18C/18F',

        self::_19A => '19A',
        self::_19B => '19B',
        self::_19C => '19C',
        self::_19F => '19F',
        self::_19BF => '19BF',
        self::_19F_VARIANT => '19F Variant',
        self::_22A => '22A',

        self::_22F => '22F',
        self::_22FA => '22F/22A',
        self::_23A => '23A',
        self::_23B => '23B',
        self::_23F => '23F',
        self::_24A => '24A',
        self::_24B => '24B',
        self::_24F => '24F',
        self::_24_ABF => '24A/24B/24F',
        self::_25A => '25A',

        self::_25F => '25F',
        self::_25_FA_38 => '25F/25A/38',

        self::_28A => '28A',
        self::_28F => '28F',

        self::_32A => '32A',
        self::_32F => '32F',
        self::_33A => '33A',
        self::_33B => '33B',
        self::_33C => '33C',
        self::_33D => '33D',

        self::_33F => '33F',
        self::_33F_VARIANT => '33F/33A/37',
        self::_35A => '35A/35C/42',
        self::_35B => '35B',
        self::_35C => '35C',

        self::_35F => '35F/47F',
        self::_35_AC_42 => '35A/35C/42',
        self::_35F_47F => '35F/47F',
        self::_38_25F_25A => '38/25F/25A',
        self::_41A => '41A',
        self::_41F => '41F',
        self::_47A => '47A',
        self::_47F => '47F',
        self::_HIGH_LYTA_VALUE => 'Spn Not able to be serotyped because of high lytA Ct value',
        self::_NON_PCV_13_SEROTYPE => 'Non PCV 13 serotype**',
        self::_NOT_DONE => 'Not done',
        self::OTHER => 'Other',
    ];
}
