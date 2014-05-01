<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of CaseStatus
 *
 */
class CaseStatus extends ArrayChoice
{
    const OPEN      = 0;
    const COMPLETE  = 1;
    const CANCELLED = 2;
    const DELETED   = 3;

    protected $values = array(
                                self::OPEN      => 'Open',
                                self::COMPLETE  => 'Complete',
                                self::CANCELLED => 'Cancelled',
                                self::DELETED   => 'Deleted',
                             );

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setDefaults(array('data_extraction_method' => 'default'))
            ->setAllowedValues(array('data_extraction_method' => array('default')))
        ;
    }

    public function getName()
    {
        return 'CaseStatus';
    }
}
