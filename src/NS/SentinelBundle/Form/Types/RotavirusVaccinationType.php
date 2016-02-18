<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of RotavirusVaccinationType
 *
 */
class RotavirusVaccinationType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const GSK = 1;
    const MERK = 2;
    const UNKNOWN = 99;


    protected $values = array(
                                self::GSK     => 'Rotarix, GSK',
                                self::MERK    => 'RotaTeq, Merck',
                                self::UNKNOWN => 'Unknown',
                             );

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'RotavirusVaccinationType';
    }
}
