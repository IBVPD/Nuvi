<?php

namespace NS\SentinelBundle\Form\Types;

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
