<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseLabType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('labId',                      null, ['required'=>true, 'property_path'=>'lab_id'])
            ->add('dateReceived',               DatePickerType::class, ['required' => false, 'label' => 'external-lab-form.date-received'])
            ->add('genotypingDate',             DatePickerType::class, ['required' => false, 'label' => 'external-lab-form.genotyping-date'])
            ->add('genotypingResultG',          GenotypeResultG::class, ['required' => false, 'label' => 'external-lab-form.genotyping-result-g'])
            ->add('genotypingResultGSpecify',   null, ['required' => false, 'label' => 'external-lab-form.genotyping-result-g-specify', 'hidden' => ['parent' => 'genotypingResultG', 'value' => [GenotypeResultG::OTHER, GenotypeResultG::MIXED]]])
            ->add('genotypeResultP',            GenotypeResultP::class, ['required' => false, 'label' => 'external-lab-form.genotyping-result-p'])
            ->add('genotypeResultPSpecify',     null, ['required' => false, 'label' => 'external-lab-form.genotyping-result-p-specify', 'hidden' => ['parent' => 'genotypeResultP', 'value' => [GenotypeResultP::OTHER, GenotypeResultP::MIXED]]])
            ->add('pcrVp6Result',               ElisaResult::class, ['required' => false, 'label' => 'external-lab-form.pcr-vp6-result'])
            ->add('comment',                    null, ['required'=>false, 'label'=>'external-lab-form.comment'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'error_bubbling' => false,
        ]);
    }
}
