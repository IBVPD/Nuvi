<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('caseId',                     null,               array('required'=>false, 'label'=>'rotavirus-form.caseId'))
            ->add('gender',                     'Gender',           array('required'=>false, 'label'=>'rotavirus-form.gender'))
            ->add('dob',                        'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.dob'))
            ->add('age',                        null,               array('required'=>false, 'label'=>'rotavirus-form.age-in-months'))
            ->add('district',                   null,               array('required'=>false, 'label'=>'rotavirus-form.district'))
            ->add('admDate',                    'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.admissionDate'))
            ->add('symptomDiarrhea',            'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrhea'))
            ->add('symptomDiarrheaOnset',       'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaOnset'))
            ->add('symptomDiarrheaEpisodes',    null,               array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaEpisodes'))
            ->add('symptomDiarrheaDuration',    null,               array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaDuration'))
            ->add('symptomDiarrheaVomit',       'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaVomit'))
            ->add('symptomVomitEpisodes',       null,               array('required'=>false, 'label'=>'rotavirus-form.symptomVomitEpisodes'))
            ->add('symptomVomitDuration',       null,               array('required'=>false, 'label'=>'rotavirus-form.symptomVomitDuration'))
            ->add('symptomDehydration',         'Dehydration',      array('required'=>false, 'label'=>'rotavirus-form.symptomDehydration'))
            ->add('rehydration',                'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.rehydration'))
            ->add('rehydrationType',            'Rehydration',      array('required'=>false, 'label'=>'rotavirus-form.rehydrationType'))
            ->add('rehydrationOther',           null,               array('required'=>false, 'label'=>'rotavirus-form.rehydrationOther'))
            ->add('vaccinationReceived',        'RotavirusVaccinationReceived', array('required'=>false, 'label'=>'rotavirus-form.vaccinationReceived'))
            ->add('vaccinationType',            'RotavirusVaccinationType',     array('required'=>false, 'label'=>'rotavirus-form.vaccinationType'))
            ->add('doses',                      'Doses',            array('required'=>false, 'label'=>'rotavirus-form.doses'))
            ->add('firstVaccinationDose',       'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.firstVaccinationDose'))
            ->add('secondVaccinationDose',      'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.secondVaccinationDose'))
            ->add('thirdVaccinationDose',       'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.thirdVaccinationDose'))
            ->add('stoolCollected',             'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolCollected'))
            ->add('stoolId',                    null,               array('required'=>false, 'label'=>'rotavirus-form.stoolId'))
            ->add('stoolCollectionDate',        'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolCollectionDate'))
            ->add('dischargeOutcome',           'DischargeOutcome', array('required'=>false, 'label'=>'rotavirus-form.dischargeOutcome'))
            ->add('dischargeDate',              'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.dischargeDate'))
            ->add('dischargeClassOther',        null,               array('required'=>false, 'label'=>'rotavirus-form.dischargeClassOther'))
            ->add('comment',                    null,               array('required'=>false, 'label'=>'rotavirus-form.comment'))
            ->add('sentToReferenceLab')
            ->add('sentToNationalLab')
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
        return 'rotavirus';
    }
}
