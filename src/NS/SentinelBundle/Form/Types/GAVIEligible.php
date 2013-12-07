<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of GAVIEligible
 *
 */
class GAVIEligible extends ArrayChoice
{
    const NEVER    = 1;
    const PREVIOUS = 2;
    const CURRENT  = 3;

    protected $values = array(
                                self::NEVER => 'Never',
                                self::PREVIOUS=>'Previous',
                                self::CURRENT=>'Current',
                             );

    public function getName()
    {
        return 'gavieligible';
    }
}
