<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of PCRResult
 *
 */
class PCRResult extends LatResult
{
    protected $values = array(
                                self::NEGATIVE => 'Negative',
                                self::SPN      => 'Spn',
                                self::HI       => 'Hi',
                                self::NM       => 'Nm',
                                self::UNKNOWN  => 'Unknown',
                             );    

    public function getName()
    {
        return 'pcrresult';
    }
}
