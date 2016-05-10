<?php

namespace NS\SentinelBundle\Form\IBD;

use \NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier;
use \NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier;
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
            ->add('sampleType',                 'NS\SentinelBundle\Form\IBD\Types\SampleType', array('label' => 'ibd-rrl-form.sample-type', 'required' => false))
            ->add('sampleCollectionDate',       'NS\AceBundle\Form\DatePickerType', array('label'=>'ibd-rrl-form.sample-collection-date', 'required'=>false))
            ->add('dateReceived',               'NS\AceBundle\Form\DatePickerType', array('label' => 'ibd-rrl-form.date-received', 'required' => false))
            ->add('isolateViable',              'NS\SentinelBundle\Form\IBD\Types\IsolateViable', array('label' => 'ibd-rrl-form.isolate-viable', 'required' => false))
            ->add('isolateType',                'NS\SentinelBundle\Form\IBD\Types\IsolateType', array('label' => 'ibd-rrl-form.isolate-type', 'required' => false))
            ->add('pathogenIdentifierMethod',   'NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier', array('label' => 'ibd-rrl-form.pathogen-id-method', 'required' => false, 'attr' => array('data-context-child' => 'pathogenIdentifierMethod')))
            ->add('pathogenIdentifierOther',    null, array('label' => 'ibd-rrl-form.pathogen-id-other', 'required' => false, 'attr' => array('data-context-parent' => 'pathogenIdentifierMethod', 'data-context-value' => PathogenIdentifier::OTHER)))
            ->add('serotypeIdentifier',         'NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier', array('label' => 'ibd-rrl-form.serotype-id-method', 'required' => false, 'attr' => array('data-context-child' => 'serotypeIdentifier')))
            ->add('serotypeIdentifierOther',    null, array('label' => 'ibd-rrl-form.serotype-id-other', 'required' => false, 'attr' => array('data-context-parent' => 'serotypeIdentifier', 'data-context-value' => SerotypeIdentifier::OTHER)))
            ->add('lytA',                       'NS\SentinelBundle\Form\IBD\Types\CTValueType', array('label' => 'ibd-rrl-form.lytA', 'required' => false))
            ->add('ctrA',                       'NS\SentinelBundle\Form\IBD\Types\CTValueType', array('label' => 'ibd-rrl-form.ctrA', 'required' => false))
            ->add('sodC',                       'NS\SentinelBundle\Form\IBD\Types\CTValueType', array('label' => 'ibd-rrl-form.sodC', 'required' => false))
            ->add('hpd1',                       'NS\SentinelBundle\Form\IBD\Types\CTValueType', array('label' => 'ibd-rrl-form.hpd1', 'required' => false))
            ->add('hpd3',                       'NS\SentinelBundle\Form\IBD\Types\CTValueType', array('label' => 'ibd-rrl-form.hpd3', 'required' => false))
            ->add('bexA',                       'NS\SentinelBundle\Form\IBD\Types\CTValueType', array('label' => 'ibd-rrl-form.bexA', 'required' => false))
            ->add('rNaseP',                     'NS\SentinelBundle\Form\IBD\Types\CTValueType', array('label' => 'ibd-rrl-form.rNasP', 'required' => false))
            ->add('finalResult',                'NS\SentinelBundle\Form\IBD\Types\FinalResult', array('label' => 'ibd-rrl-form.finalResult', 'required' => false))
            ->add('spnSerotype',                'NS\SentinelBundle\Form\IBD\Types\SpnSerotype', array('label' => 'ibd-rrl-form.spnSerotype', 'required' => false))
            ->add('hiSerotype',                 'NS\SentinelBundle\Form\IBD\Types\HiSerotype', array('label' => 'ibd-rrl-form.hiSerotype', 'required' => false))
            ->add('nmSerogroup',                'NS\SentinelBundle\Form\IBD\Types\NmSerogroup', array('label' => 'ibd-rrl-form.nmSerogroup', 'required' => false))
            ->add('comment',                    null, array('label' => 'ibd-rrl-form.comment', 'required' => false));
    }
}
