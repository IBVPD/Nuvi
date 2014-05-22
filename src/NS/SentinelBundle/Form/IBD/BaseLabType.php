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
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('labId',                      null,                   array('label'=>'meningitis-rrl-form.lab-id','required'=>true))
            ->add('sampleType',                 'SampleType',           array('label'=>'meningitis-rrl-form.sample-type','required'=>false))
            ->add('dateReceived',               'acedatepicker',        array('label'=>'meningitis-rrl-form.date-received','required'=>false))
            ->add('volume',                     'Volume',               array('label'=>'meningitis-rrl-form.volume','required'=>false))
            ->add('DNAExtractionDate',          'acedatepicker',        array('label'=>'meningitis-rrl-form.dna-extraction-date','required'=>false))
            ->add('DNAVolume',                  null,                   array('label'=>'meningitis-rrl-form.dna-volume','required'=>false))
            ->add('isolateViable',              'TripleChoice',         array('label'=>'meningitis-rrl-form.isolate-viable','required'=>false))
            ->add('isolateType',                'IsolateType',          array('label'=>'meningitis-rrl-form.isolate-type','required'=>false))
            ->add('pathogenIdentifierMethod',   'PathogenIdentifier',   array('label'=>'meningitis-rrl-form.pathogen-id-method','required'=>false, 'attr' => array('data-context-child'=>'pathogenIdentifierMethod')))
            ->add('pathogenIdentifierOther',    null,                   array('label'=>'meningitis-rrl-form.pathogen-id-other', 'required'=>false, 'attr' => array('data-context-parent'=>'pathogenIdentifierMethod', 'data-context-value'=>PathogenIdentifier::OTHER)))
            ->add('serotypeIdentifier',         'SerotypeIdentifier',   array('label'=>'meningitis-rrl-form.serotype-id-method','required'=>false, 'attr' => array('data-context-child'=>'serotypeIdentifier')))
            ->add('serotypeIdentifierOther',    null,                   array('label'=>'meningitis-rrl-form.serotype-id-other', 'required'=>false, 'attr' => array('data-context-parent'=>'serotypeIdentifier', 'data-context-value'=>SerotypeIdentifier::OTHER)))
            ->add('lytA',                       null,                   array('label'=>'meningitis-rrl-form.lytA','required'=>false))
            ->add('sodC',                       null,                   array('label'=>'meningitis-rrl-form.sodC','required'=>false))
            ->add('hpd',                        null,                   array('label'=>'meningitis-rrl-form.hpd','required'=>false))
            ->add('rNaseP',                     null,                   array('label'=>'meningitis-rrl-form.rNasP','required'=>false))
            ->add('spnSerotype',                'SpnSerotype',          array('label'=>'meningitis-rrl-form.spnSerotype','required'=>false))
            ->add('hiSerotype',                 'HiSerotype',           array('label'=>'meningitis-rrl-form.hiSerotype','required'=>false))
            ->add('nmSerogroup',                'NmSerogroup',          array('label'=>'meningitis-rrl-form.nmSerogroup','required'=>false))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ibd_base_lab';
    }
}
