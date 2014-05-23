<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableSetChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of SampleType
 *
 */
class SampleType extends TranslatableSetChoice implements TranslationContainerInterface
{
    const CSF        = 1;
    const ISOLATE    = 2;
    const WHOLE      = 3;
    const BROTH      = 4;
    const PLEURAL    = 5;
    const INOCULATED = 6;

    protected $set = array(
                                self::CSF        => 'CSF',
                                self::ISOLATE    => 'Isolate',
                                self::WHOLE      => 'Whole Blood',
                                self::BROTH      => 'Blood culture broth',
                                self::PLEURAL    => 'Pleural Fluid',
                                self::INOCULATED => 'Inoculated',
                             );

    public function getName()
    {
        return 'SampleType';
    }

    public function getIndexForValue($value)
    {
        return array_search($value,$this->set);
    }

    public function getValues()
    {
        return $this->set;
    }
}
