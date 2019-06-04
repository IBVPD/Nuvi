<?php

namespace NS\SentinelBundle\Filter\Type\Pneumonia;

use NS\SentinelBundle\Filter\Type\BaseReportFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportFilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getParent(): string
    {
        return BaseReportFilterType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'pneu_report_filter';
    }
}
