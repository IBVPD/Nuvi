<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of CultureResult
 *
 */
class CultureResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NEGATIVE      = 0;
    const SPN           = 1;
    const HI            = 2;
    const NM            = 3;
    const OTHER         = 4;
    const CONTAMINANT   = 5;
    const UNKNOWN       = 99;

    protected $values = [
                                self::NEGATIVE      => 'Negative',
                                self::SPN           => 'Spn',
                                self::HI            => 'Hi',
                                self::NM            => 'Nm',
                                self::OTHER         => 'Other',
                                self::CONTAMINANT   => 'Contaminant',
                                self::UNKNOWN       => 'Unknown',
    ];
}
