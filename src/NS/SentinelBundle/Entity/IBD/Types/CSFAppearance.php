<?php

namespace NS\SentinelBundle\Entity\IBD\Types;

use NS\UtilBundle\Entity\Types\ArrayChoice;

/**
 * Description of TripleChoice
 * TODO remove once all live and ibd_cases table has been deleted
 *
 * @author gnat
 */
class CSFAppearance extends ArrayChoice
{
    /**
     * @var string
     */
    protected $convert_class = '\NS\SentinelBundle\Form\IBD\Types\CSFAppearance';

    /**
     * @return string
     */
    public function getName()
    {
        return 'CSFAppearance';
    }
}
