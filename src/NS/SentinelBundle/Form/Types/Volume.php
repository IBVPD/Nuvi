<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of Volume
 *
 */
class Volume extends ArrayChoice
{
    const YES = 1;
    const NO  = 2;

    protected $values = array(
                                self::YES => '≥200µl',
                                self::NO  => '<200µl',
                             );

    public function getName()
    {
        return 'volume';
    }
}
