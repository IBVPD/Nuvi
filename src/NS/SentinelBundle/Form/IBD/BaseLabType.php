<?php

namespace NS\SentinelBundle\Form\IBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;

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
            ->add('labId', null, array('label' => 'ibd-rrl-form.lab-id', 'required' => true))
            ->add('sampleType', 'SampleType', array('label'    => 'ibd-rrl-form.sample-type',
                'required' => false))
            ->add('dateReceived', 'acedatepicker', array('label'    => 'ibd-rrl-form.date-received',
                'required' => false))
            ->add('isolateViable', 'AlternateTripleChoice', array('label'    => 'ibd-rrl-form.isolate-viable',
                'required' => false))
            ->add('isolateType', 'IsolateType', array('label'    => 'ibd-rrl-form.isolate-type',
                'required' => false))
            ->add('pathogenIdentifierMethod', 'PathogenIdentifier', array('label'    => 'ibd-rrl-form.pathogen-id-method',
                'required' => false, 'attr' => array('data-context-child' => 'pathogenIdentifierMethod')))
            ->add('pathogenIdentifierOther', null, array('label'    => 'ibd-rrl-form.pathogen-id-other',
                'required' => false, 'attr' => array('data-context-parent' => 'pathogenIdentifierMethod',
                    'data-context-value' => PathogenIdentifier::OTHER)))
            ->add('serotypeIdentifier', 'SerotypeIdentifier', array('label'    => 'ibd-rrl-form.serotype-id-method',
                'required' => false, 'attr' => array('data-context-child' => 'serotypeIdentifier')))
            ->add('serotypeIdentifierOther', null, array('label'    => 'ibd-rrl-form.serotype-id-other',
                'required' => false, 'attr' => array('data-context-parent' => 'serotypeIdentifier',
                    'data-context-value' => SerotypeIdentifier::OTHER)))
            ->add('lytA', null, array('label' => 'ibd-rrl-form.lytA', 'required' => false))
            ->add('ctrA', null, array('label' => 'ibd-rrl-form.ctrA', 'required' => false))
            ->add('sodC', null, array('label' => 'ibd-rrl-form.sodC', 'required' => false))
            ->add('hpd1', null, array('label' => 'ibd-rrl-form.hpd1', 'required' => false))
            ->add('hpd3', null, array('label' => 'ibd-rrl-form.hpd3', 'required' => false))
            ->add('bexA', null, array('label' => 'ibd-rrl-form.bexA', 'required' => false))
            ->add('rNaseP', null, array('label' => 'ibd-rrl-form.rNasP', 'required' => false))
            ->add('finalResult', 'FinalResult', array('label' => 'ibd-rrl-form.finalResult', 'required' => false))
            ->add('spnSerotype', 'SpnSerotype', array('label'    => 'ibd-rrl-form.spnSerotype',
                'required' => false))
            ->add('hiSerotype', 'HiSerotype', array('label'    => 'ibd-rrl-form.hiSerotype',
                'required' => false))
            ->add('nmSerogroup', 'NmSerogroup', array('label'    => 'ibd-rrl-form.nmSerogroup',
                'required' => false))
            ->add('comment',null,array('label'=>'ibd-rrl-form.comment','required'=>false))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ibd_base_lab';
    }
}
