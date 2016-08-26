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
            ->add('dateReceived',               DatePickerType::class,       array('required'=>false, 'label'=>'external-lab-form.date-received'))
            ->add('genotypingDate',             DatePickerType::class,     array('required'=>false, 'label'=>'external-lab-form.genotyping-date'))
            ->add('genotypingResultG',          GenotypeResultG::class, array('required'=>false, 'label'=>'external-lab-form.genotyping-result-g', 'hidden-child' => 'genotypingResultG'))
            ->add('genotypingResultGSpecify',   null,      array('required'=>false, 'label'=>'external-lab-form.genotyping-result-g-specify', 'hidden-parent' => 'genotypingResultG', 'hidden-value' => json_encode(array(GenotypeResultG::OTHER, GenotypeResultG::MIXED))))
            ->add('genotypeResultP',            GenotypeResultP::class,  array('required'=>false, 'label'=>'external-lab-form.genotyping-result-p', 'hidden-child' => 'genotypingResultP'))
            ->add('genotypeResultPSpecify',     null,        array('required'=>false, 'label'=>'external-lab-form.genotyping-result-p-specify', 'hidden-parent' => 'genotypingResultP', 'hidden-value' => json_encode(array(GenotypeResultP::OTHER, GenotypeResultP::MIXED))))
            ->add('pcrVp6Result',               ElisaResult::class, array('required' => false, 'label' => 'external-lab-form.pcr-vp6-result'))
            ->add('comment',                    null, array('required'=>false, 'label'=>'external-lab-form.comment'))
        ;
    }
}
