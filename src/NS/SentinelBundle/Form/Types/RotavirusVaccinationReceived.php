<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of RotavirusVaccinationReceived
 *
 */
class RotavirusVaccinationReceived extends ArrayChoice
{
    const NO          = 0;
    const YES_HISTORY = 1;
    const YES_CARD    = 2;
    const UNKNOWN     = 99;

    protected $values = array(
                                self::NO            => 'No',
                                self::YES_HISTORY   => 'Yes-By-History',
                                self::YES_CARD      => 'Yes-By-Card',
                                self::UNKNOWN       => 'Unknown',
                             );

    public function getName()
    {
        return 'RotavirusVaccinationReceived';
    }
}
