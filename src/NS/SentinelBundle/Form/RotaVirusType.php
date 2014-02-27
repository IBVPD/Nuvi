<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RotaVirusType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('caseId')
            ->add('gender')
            ->add('dob')
            ->add('age')
            ->add('district')
            ->add('admissionDate')
            ->add('symptomDiarrhea')
            ->add('symptomDiarrheaOnset')
            ->add('symptomDiarrheaEpisodes')
            ->add('symptomDiarrheaDuration')
            ->add('symptomDiarrheaVomit')
            ->add('symptomVomitEpisodes')
            ->add('symptomVomitDuration')
            ->add('symptomDehydration')
            ->add('rehydration')
            ->add('rehydrationType')
            ->add('rehydrationOther')
            ->add('vaccinationReceived')
            ->add('vaccinationType')
            ->add('doses')
            ->add('firstVaccinationDose')
            ->add('secondVaccinationDose')
            ->add('thirdVaccinationDose')
            ->add('stoolCollected')
            ->add('stoolId')
            ->add('stoolCollectionDate')
            ->add('dischargeOutcome')
            ->add('dischargeDate')
            ->add('dischargeClassOther')
            ->add('comment')
            ->add('region')
            ->add('country')
            ->add('site')
            ->add('lab')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\RotaVirus'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ns_sentinelbundle_rotavirus';
    }
}
