<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;

/**
 * Class BaseLabType
 * @package NS\SentinelBundle\Form\Rota
 */
class BaseLabType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('labId')
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
}
