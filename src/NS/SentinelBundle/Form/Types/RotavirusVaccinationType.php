<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of RotavirusVaccinationType
 *
 */
class RotavirusVaccinationType extends TranslatableArrayChoice
{
    const GSK = 1;
    const MERK = 2;
    const UNKNOWN = 99;


    protected $values = array(
                                self::GSK     => 'Rotarix, GSK',
                                self::MERK    => 'RotaTeq, Merk',
                                self::UNKNOWN => 'Unknown',
                             );

    public function getName()
    {
        return 'RotavirusVaccinationType';
    }
}
