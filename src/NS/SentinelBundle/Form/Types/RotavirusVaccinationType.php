<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of RotavirusVaccinationType
 *
 */
class RotavirusVaccinationType extends ArrayChoice
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
