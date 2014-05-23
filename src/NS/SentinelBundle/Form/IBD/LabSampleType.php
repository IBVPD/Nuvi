<?php

namespace NS\SentinelBundle\Form\IBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;

/**
 * Description of LabSamples
 *
 * @author gnat
 */
class LabSampleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type',                       'hidden')
            ->add('pathogenIdentifierMethod',   'PathogenIdentifier',   array('label'=>'meningitis-rrl-form.pathogen-id-method','required'=>false))//, 'attr' => array('data-context-child'=>'pathogenIdentifierMethod')))
            ->add('pathogenIdentifierOther',    null,                   array('label'=>'meningitis-rrl-form.pathogen-id-other', 'required'=>false))//, 'attr' => array('data-context-parent'=>'pathogenIdentifierMethod', 'data-context-value'=>PathogenIdentifier::OTHER)))
            ->add('serotypeIdentifier',         'SerotypeIdentifier',   array('label'=>'meningitis-rrl-form.serotype-id-method','required'=>false))//, 'attr' => array('data-context-child'=>'serotypeIdentifier')))
            ->add('serotypeIdentifierOther',    null,                   array('label'=>'meningitis-rrl-form.serotype-id-other', 'required'=>false))//, 'attr' => array('data-context-parent'=>'serotypeIdentifier', 'data-context-value'=>SerotypeIdentifier::OTHER)))
            ->add('lytA',                       null,                   array('label'=>'meningitis-rrl-form.lytA','required'=>false))
            ->add('sodC',                       null,                   array('label'=>'meningitis-rrl-form.sodC','required'=>false))
            ->add('hpd',                        null,                   array('label'=>'meningitis-rrl-form.hpd','required'=>false))
            ->add('rNaseP',                     null,                   array('label'=>'meningitis-rrl-form.rNasP','required'=>false))
            ->add('spnSerotype',                'SpnSerotype',          array('label'=>'meningitis-rrl-form.spnSerotype','required'=>false))
            ->add('spnSerotypeOther',           null,                   array('label'=>'meningitis-rrl-form.spnSerotype-other','required'=>false))
            ->add('hiSerotype',                 'HiSerotype',           array('label'=>'meningitis-rrl-form.hiSerotype','required'=>false))
            ->add('hiSerotypeOther',            null,                   array('label'=>'meningitis-rrl-form.hiSerotype-other','required'=>false))
            ->add('nmSerogroup',                'NmSerogroup',          array('label'=>'meningitis-rrl-form.nmSerogroup','required'=>false))
            ->add('nmSerogroupOther',           null,                   array('label'=>'meningitis-rrl-form.nmSerogroup-other','required'=>false))
                ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\IBD\ExternalLabSample'
        ));
    }

    public function getName()
    {
        return 'ibd_lab_sample';
    }
}
