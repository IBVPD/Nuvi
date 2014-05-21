<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\Dehydration;

class CaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName',                   null,               array('required'=>false, 'label'=>'rotavirus-form.last-name'))
            ->add('firstName',                  null,               array('required'=>false, 'label'=>'rotavirus-form.first-name'))
            ->add('parentalName',               null,               array('required'=>false, 'label'=>'rotavirus-form.parental-name'))
            ->add('caseId',                     null,               array('required'=>false, 'label'=>'rotavirus-form.caseId'))
            ->add('gender',                     'Gender',           array('required'=>false, 'label'=>'rotavirus-form.gender'))
            ->add('dob',                        'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.dob'))
            ->add('age',                        null,               array('required'=>false, 'label'=>'rotavirus-form.age-in-months'))
            ->add('district',                   null,               array('required'=>false, 'label'=>'rotavirus-form.district'))
            ->add('admDate',                    'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.admissionDate'))

            ->add('symptomDiarrhea',            'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrhea',         'attr' => array('data-context-child'=>'vaccineReceived')))
            ->add('symptomDiarrheaOnset',       'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaOnset',    'attr' => array('data-context-parent'=>'vaccineReceived','data-context-value'=>  TripleChoice::YES)))
            ->add('symptomDiarrheaEpisodes',    null,               array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaEpisodes', 'attr' => array('data-context-parent'=>'vaccineReceived','data-context-value'=>  TripleChoice::YES)))
            ->add('symptomDiarrheaDuration',    null,               array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaDuration', 'attr' => array('data-context-parent'=>'vaccineReceived','data-context-value'=>  TripleChoice::YES)))

            ->add('symptomVomit',               'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.symptomVomit',            'attr' => array('data-context-child'=>'symptomVomit')))
            ->add('symptomVomitEpisodes',       null,               array('required'=>false, 'label'=>'rotavirus-form.symptomVomitEpisodes',    'attr' => array('data-context-parent'=>'symptomVomit','data-context-value'=>  TripleChoice::YES)))
            ->add('symptomVomitDuration',       null,               array('required'=>false, 'label'=>'rotavirus-form.symptomVomitDuration',    'attr' => array('data-context-parent'=>'symptomVomit','data-context-value'=>  TripleChoice::YES)))

            ->add('symptomDehydration',         'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.symptomDehydration',       'attr' => array('data-context-child'=>'symptomDehydration')))
            ->add('symptomDehydrationAmount',   'Dehydration',      array('required'=>false, 'label'=>'rotavirus-form.symptomDehydrationAmount', 'attr' => array('data-context-parent'=>'symptomDehydration', 'data-context-child'=>'symptomDehydrationAmount','data-context-value'=>TripleChoice::YES)))
            ->add('rehydration',                'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.rehydration',              'attr' => array('data-context-parent'=>'symptomDehydrationAmount','data-context-value'=> json_encode(array(Dehydration::SOME,Dehydration::SEVERE)))))
            ->add('rehydrationType',            'Rehydration',      array('required'=>false, 'label'=>'rotavirus-form.rehydrationType',          'attr' => array('data-context-parent'=>'symptomDehydrationAmount','data-context-value'=> json_encode(array(Dehydration::SOME,Dehydration::SEVERE)))))
            ->add('rehydrationOther',           null,               array('required'=>false, 'label'=>'rotavirus-form.rehydrationOther',         'attr' => array('data-context-parent'=>'symptomDehydrationAmount','data-context-value'=> json_encode(array(Dehydration::SOME,Dehydration::SEVERE)))))

            ->add('vaccinationReceived',        'RotavirusVaccinationReceived', array('required'=>false, 'label'=>'rotavirus-form.vaccinationReceived', 'attr' => array('data-context-child'=>'vaccineReceived')))
            ->add('vaccinationType',            'RotavirusVaccinationType',     array('required'=>false, 'label'=>'rotavirus-form.vaccinationType',     'attr' => array('data-context-parent'=>'vaccineReceived', 'data-context-value'=>json_encode(array(RotavirusVaccinationReceived::YES_CARD,RotavirusVaccinationReceived::YES_HISTORY)))))
            ->add('doses',                      'RotavirusDoses',   array('required'=>false, 'label'=>'rotavirus-form.doses',                           'attr' => array('data-context-parent'=>'vaccineReceived', 'data-context-value'=>json_encode(array(RotavirusVaccinationReceived::YES_CARD,RotavirusVaccinationReceived::YES_HISTORY)))))
            ->add('firstVaccinationDose',       'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.firstVaccinationDose',            'attr' => array('data-context-parent'=>'vaccineReceived', 'data-context-value'=>json_encode(array(RotavirusVaccinationReceived::YES_CARD,RotavirusVaccinationReceived::YES_HISTORY)))))
            ->add('secondVaccinationDose',      'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.secondVaccinationDose',           'attr' => array('data-context-parent'=>'vaccineReceived', 'data-context-value'=>json_encode(array(RotavirusVaccinationReceived::YES_CARD,RotavirusVaccinationReceived::YES_HISTORY)))))
            ->add('thirdVaccinationDose',       'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.thirdVaccinationDose',            'attr' => array('data-context-parent'=>'vaccineReceived', 'data-context-value'=>json_encode(array(RotavirusVaccinationReceived::YES_CARD,RotavirusVaccinationReceived::YES_HISTORY)))))

            ->add('stoolCollected',             'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolCollected',  'attr' => array('data-context-child'=>'stoolCollected')))
            ->add('stoolId',                    null,               array('required'=>false, 'label'=>'rotavirus-form.stoolId',         'attr' => array('data-context-parent'=>'stoolCollected','data-context-value'=>  TripleChoice::YES)))
            ->add('stoolCollectionDate',        'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolCollectionDate',         'attr' => array('data-context-parent'=>'stoolCollected','data-context-value'=>  TripleChoice::YES)))
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
