<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of MeningitisVaccinationType
 *
 */
class MeningitisVaccinationType extends ArrayChoice
{
    const MEN_AFR_VAC     = 1;
    const ACW135          = 2;
    const ACY135          = 3;
    const CONJUGATE_MEN_A = 4;
    const UNKNOWN         = 99;

    protected $values = array(
                            self::MEN_AFR_VAC => 'MenAfrVac',
                            self::ACW135 => 'ACW135',
                            self::ACY135 => 'ACY135',
                            self::CONJUGATE_MEN_A => 'Conjugate Men A',
                            self::UNKNOWN => 'Unknown',
                             );

    public function getName()
    {
        return 'MeningitisVaccinationType';
    }
}
