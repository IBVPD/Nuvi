<?php

namespace NS\SentinelBundle\Form\Pneumonia;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Form\IBD\Types\CTValueType;
use NS\SentinelBundle\Form\IBD\Types\FinalResult;
use NS\SentinelBundle\Form\IBD\Types\HiSerotype;
use NS\SentinelBundle\Form\IBD\Types\IsolateType;
use NS\SentinelBundle\Form\IBD\Types\IsolateViable;
use NS\SentinelBundle\Form\IBD\Types\NmSerogroup;
use NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\IBD\Types\SampleType;
use NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\IBD\Types\SpnSerotype;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BaseLabType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('labId',                      null, ['label' => 'ibd-rrl-form.lab-id', 'required' => true, 'property_path' => 'lab_id'])
            ->add('sampleType',                 SampleType::class, ['label' => 'ibd-rrl-form.sample-type', 'required' => false])
            ->add('dateReceived',               DatePickerType::class, ['label' => 'ibd-rrl-form.date-received', 'required' => false])
            ->add('isolateViable',              IsolateViable::class, ['label' => 'ibd-rrl-form.isolate-viable', 'required' => false, 'hidden' => ['parent' => 'sampleType', 'value' => [SampleType::ISOLATE, SampleType::CSF_ISOLATE]]])
            ->add('isolateType',                IsolateType::class, ['label' => 'ibd-rrl-form.isolate-type', 'required' => false, 'hidden' => ['parent' => 'sampleType', 'value' => [SampleType::ISOLATE, SampleType::CSF_ISOLATE]]])
            ->add('pathogenIdentifierMethod',   PathogenIdentifier::class, ['label' => 'ibd-rrl-form.pathogen-id-method', 'required' => false])
            ->add('pathogenIdentifierOther',    null, ['label' => 'ibd-rrl-form.pathogen-id-other', 'required' => false, 'hidden' => ['parent' => 'pathogenIdentifierMethod', 'value' => PathogenIdentifier::OTHER]])
            ->add('serotypeIdentifier',         SerotypeIdentifier::class, ['label' => 'ibd-rrl-form.serotype-id-method', 'required' => false])
            ->add('serotypeIdentifierOther',    null, ['label' => 'ibd-rrl-form.serotype-id-other', 'required' => false, 'hidden' => ['parent' => 'serotypeIdentifier', 'value' => SerotypeIdentifier::OTHER]])
            ->add('lytA',                       CTValueType::class, ['label' => 'ibd-rrl-form.lytA', 'required' => false])
            ->add('ctrA',                       CTValueType::class, ['label' => 'ibd-rrl-form.ctrA', 'required' => false])
            ->add('sodC',                       CTValueType::class, ['label' => 'ibd-rrl-form.sodC', 'required' => false])
            ->add('hpd1',                       CTValueType::class, ['label' => 'ibd-rrl-form.hpd1', 'required' => false])
            ->add('hpd3',                       CTValueType::class, ['label' => 'ibd-rrl-form.hpd3', 'required' => false])
            ->add('bexA',                       CTValueType::class, ['label' => 'ibd-rrl-form.bexA', 'required' => false])
            ->add('rNaseP',                     CTValueType::class, ['label' => 'ibd-rrl-form.rNasP', 'required' => false])
            ->add('finalResult',                FinalResult::class, ['label' => 'ibd-rrl-form.finalResult', 'required' => false])
            ->add('spnSerotype',                SpnSerotype::class, ['label' => 'ibd-rrl-form.spnSerotype', 'required' => false, 'hidden' => ['parent' => 'finalResult', 'value' => [FinalResult::SPN, FinalResult::SPN_HI, FinalResult::SPN_HI_NM, FinalResult::SPN_NM]]])
            ->add('hiSerotype',                 HiSerotype::class, ['label' => 'ibd-rrl-form.hiSerotype', 'required' => false, 'hidden' => ['parent' => 'finalResult', 'value' => [FinalResult::HI, FinalResult::HI_NM, FinalResult::SPN_HI_NM, FinalResult::SPN_HI]]])
            ->add('nmSerogroup',                NmSerogroup::class, ['label' => 'ibd-rrl-form.nmSerogroup', 'required' => false, 'hidden' => ['parent' => 'finalResult', 'value' => [FinalResult::NM, FinalResult::HI_NM, FinalResult::SPN_HI_NM, FinalResult::SPN_NM]]])
            ->add('comment',                    null, ['label' => 'ibd-rrl-form.comment', 'required' => false]);
    }
}
