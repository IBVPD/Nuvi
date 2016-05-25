<?php

namespace NS\SentinelBundle\Form\IBD;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Form\IBD\Types\CTValueType;
use NS\SentinelBundle\Form\IBD\Types\FinalResult;
use NS\SentinelBundle\Form\IBD\Types\HiSerotype;
use NS\SentinelBundle\Form\IBD\Types\IsolateType;
use NS\SentinelBundle\Form\IBD\Types\IsolateViable;
use NS\SentinelBundle\Form\IBD\Types\NmSerogroup;
use \NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\IBD\Types\SampleType;
use \NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\IBD\Types\SpnSerotype;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;

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
            ->add('labId',                      null, array('label' => 'ibd-rrl-form.lab-id', 'required' => true))
            ->add('sampleType',                 SampleType::class, array('label' => 'ibd-rrl-form.sample-type', 'required' => false))
            ->add('sampleCollectionDate',       DatePickerType::class, array('label'=>'ibd-rrl-form.sample-collection-date', 'required'=>false))
            ->add('dateReceived',               DatePickerType::class, array('label' => 'ibd-rrl-form.date-received', 'required' => false))
            ->add('isolateViable',              IsolateViable::class, array('label' => 'ibd-rrl-form.isolate-viable', 'required' => false))
            ->add('isolateType',                IsolateType::class, array('label' => 'ibd-rrl-form.isolate-type', 'required' => false))
            ->add('pathogenIdentifierMethod',   PathogenIdentifier::class, array('label' => 'ibd-rrl-form.pathogen-id-method', 'required' => false, 'attr' => array('data-context-child' => 'pathogenIdentifierMethod')))
            ->add('pathogenIdentifierOther',    null, array('label' => 'ibd-rrl-form.pathogen-id-other', 'required' => false, 'attr' => array('data-context-parent' => 'pathogenIdentifierMethod', 'data-context-value' => PathogenIdentifier::OTHER)))
            ->add('serotypeIdentifier',         SerotypeIdentifier::class, array('label' => 'ibd-rrl-form.serotype-id-method', 'required' => false, 'attr' => array('data-context-child' => 'serotypeIdentifier')))
            ->add('serotypeIdentifierOther',    null, array('label' => 'ibd-rrl-form.serotype-id-other', 'required' => false, 'attr' => array('data-context-parent' => 'serotypeIdentifier', 'data-context-value' => SerotypeIdentifier::OTHER)))
            ->add('lytA',                       CTValueType::class, array('label' => 'ibd-rrl-form.lytA', 'required' => false))
            ->add('ctrA',                       CTValueType::class, array('label' => 'ibd-rrl-form.ctrA', 'required' => false))
            ->add('sodC',                       CTValueType::class, array('label' => 'ibd-rrl-form.sodC', 'required' => false))
            ->add('hpd1',                       CTValueType::class, array('label' => 'ibd-rrl-form.hpd1', 'required' => false))
            ->add('hpd3',                       CTValueType::class, array('label' => 'ibd-rrl-form.hpd3', 'required' => false))
            ->add('bexA',                       CTValueType::class, array('label' => 'ibd-rrl-form.bexA', 'required' => false))
            ->add('rNaseP',                     CTValueType::class, array('label' => 'ibd-rrl-form.rNasP', 'required' => false))
            ->add('finalResult',                FinalResult::class, array('label' => 'ibd-rrl-form.finalResult', 'required' => false))
            ->add('spnSerotype',                SpnSerotype::class, array('label' => 'ibd-rrl-form.spnSerotype', 'required' => false))
            ->add('hiSerotype',                 HiSerotype::class, array('label' => 'ibd-rrl-form.hiSerotype', 'required' => false))
            ->add('nmSerogroup',                NmSerogroup::class, array('label' => 'ibd-rrl-form.nmSerogroup', 'required' => false))
            ->add('comment',                    null, array('label' => 'ibd-rrl-form.comment', 'required' => false));
    }
}
