<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of MeningitisVaccinationType
 *
 */
class MeningitisVaccinationType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const MEN_AFR_VAC     = 1;
    const ACYW135_POLY    = 2;
    const ACW135          = 3;
    const ACYW135_CON     = 4;
    const OTHER           = 5;
    const UNKNOWN         = 99;

    protected $values = array(
                            self::MEN_AFR_VAC  => 'MenAfriVac (conjugate MenA)',
                            self::ACYW135_POLY => 'ACYW135 (polysaccharide)',
                            self::ACW135       => 'ACW135 (polysaccharide)',
                            self::ACYW135_CON  => 'ACYW135 (conjugate)',
                            self::OTHER        => 'Other',
                            self::UNKNOWN      => 'Unknown',
                             );

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'MeningitisVaccinationType';
    }
}
