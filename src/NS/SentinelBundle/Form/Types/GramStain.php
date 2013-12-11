<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of GramStain
 *
 */
class GramStain extends ArrayChoice
{
    const NO_ORGANISM_DETECTED = 0;
    const GM_NEG_DIPLOCOCCI    = 1;
    const GM_NEG_COCCOBACILLI  = 2;
    const GM_NEG_RODS          = 3;
    const GM_POS_COCCI_PAIRS   = 4;
    const GM_POS_COCCI_CLUSTER = 5;
    const MIXED                = 6;
    const OTHER                = 7;
    const UNKNOWN              = 99;

    protected $values = array(
                                self::NO_ORGANISM_DETECTED => 'No Organism Detected',
                                self::GM_NEG_DIPLOCOCCI    => 'Gm neg diplococci',
                                self::GM_NEG_COCCOBACILLI  => 'Gm neg coccobacilli',
                                self::GM_NEG_RODS          => 'Gm neg rods',
                                self::GM_POS_COCCI_PAIRS   => 'Gm pos cocci pairs',
                                self::GM_POS_COCCI_CLUSTER => 'Gm pos cocci clusters',
                                self::MIXED                => 'Mixed',
                                self::OTHER                => 'Other',
                                self::UNKNOWN              => 'Unknown',
                             );

    public function getName()
    {
        return 'gramstain';
    }
}
