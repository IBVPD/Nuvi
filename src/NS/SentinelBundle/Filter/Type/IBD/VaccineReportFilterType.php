<?php declare(strict_types=1);

namespace NS\SentinelBundle\Filter\Type\IBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class VaccineReportFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('vaccine', ChoiceType::class, [
            'choices' => [
                'HIB' => 'hib_doses',
                'PCV' => 'pcv_doses',
            ],
            'mapped' => false,
            'apply_filter' => static function() {}
        ]);
    }

    public function getParent(): string
    {
        return ReportFilterType::class;
    }
}
