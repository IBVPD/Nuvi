<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of BinaxResult
 *
 */
class BinaxResult extends ArrayChoice
{
    const NEGATIVE     = 0;
    const POSITIVE     = 1;
    const INCONCLUSIVE = 2;
    const UNKNOWN      = 99;

    protected $values = array(
                                self::NEGATIVE      => 'Negative',
                                self::POSITIVE      => 'Positive',
                                self::INCONCLUSIVE  => 'Inconclusive',
                                self::UNKNOWN       => 'Unknown',
                             );

    public function getName()
    {
        return 'binaxresult';
    }
}
