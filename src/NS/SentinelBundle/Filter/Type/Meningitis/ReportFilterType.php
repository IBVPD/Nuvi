<?php

namespace NS\SentinelBundle\Filter\Type\Meningitis;

use NS\SentinelBundle\Filter\Entity\Meningitis;
use NS\SentinelBundle\Filter\Type\BaseReportFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ReportFilterType
 *
 * @author gnat
 */
class ReportFilterType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return BaseReportFilterType::class;
    }

    public function getBlockPrefix()
    {
        return 'mening_report_filter';
    }
}
