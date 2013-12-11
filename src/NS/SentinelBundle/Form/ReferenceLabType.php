<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReferenceLabType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sampleType')
            ->add('dateReceived')
            ->add('volume')
            ->add('DNAExtractionDate')
            ->add('DNAVolume')
            ->add('isolateViable')
            ->add('isolateType')
            ->add('pathogenIdentifierMethod')
            ->add('pathogenIdentierOther')
            ->add('serotypeIdentifier')
            ->add('serotypeIdentifierOther')
            ->add('lytA')
            ->add('sodC')
            ->add('hpd')
            ->add('rNaseP')
            ->add('spnSerotype')
            ->add('hiSerotype')
            ->add('nmSerogroup')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\ReferenceLab'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ns_sentinelbundle_referencelab';
    }
}
