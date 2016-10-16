<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeClassification;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome;
use NS\SentinelBundle\Form\RotaVirus\Types\Rehydration;
use NS\SentinelBundle\Form\RotaVirus\Types\VaccinationType;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\ValidatorGroup\ValidatorGroupResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\ThreeDoses;
use NS\SentinelBundle\Form\RotaVirus\Types\Dehydration;

/**
 * Class CaseType
 * @package NS\SentinelBundle\Form\Rota
 */
class CaseType extends AbstractType
{
    /**
     * @var ValidatorGroupResolver
     */
    private $validatorResolver;

    /**
     * CaseType constructor.
     * @param ValidatorGroupResolver $validatorResolver
     */
    public function __construct(ValidatorGroupResolver $validatorResolver)
    {
        $this->validatorResolver = $validatorResolver;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $required = (isset($options['method']) && $options['method'] == 'PUT');

        $builder
            ->add('lastName',                   null,               array('required'=>$required, 'label'=>'rotavirus-form.last-name', 'property_path' => 'lastName'))
            ->add('firstName',                  null,               array('required'=>$required, 'label'=>'rotavirus-form.first-name', 'property_path' => 'firstName'))
            ->add('parentalName',               null,               array('required'=>$required, 'label'=>'rotavirus-form.parental-name'))
            ->add('caseId',                     null,               array('required'=>true,      'label'=>'rotavirus-form.caseId', 'property_path' => 'case_id'))
            ->add('gender',                     Gender::class,              array('required'=>$required, 'label'=>'rotavirus-form.gender', 'property_path' => 'gender'))
            ->add('dobKnown',                   TripleChoice::class,        array('required'=>$required, 'label' => 'ibd-form.date-of-birth-known', 'hidden-child' => 'dob'))
            ->add('birthdate',                  DatePickerType::class,      array('required'=>$required, 'label' => 'ibd-form.date-of-birth', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::YES, 'widget' => 'single_text', 'property_path' => 'birthdate'))
            ->add('dobYears',                   null,                       array('required'=>$required, 'label' => 'ibd-form.date-of-birth-years', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::NO))
            ->add('dobMonths',                  null,                       array('required'=>$required, 'label' => 'ibd-form.date-of-birth-months', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::NO))
            ->add('district',                   null,                       array('required'=>$required, 'label'=>'rotavirus-form.district'))
            ->add('state',                      null,                       array('required'=>$required, 'label'=>'rotavirus-form.state'))
            ->add('admDate',                    DatePickerType::class,      array('required'=>$required, 'label'=>'rotavirus-form.admissionDate', 'property_path' => 'amd_date'))

            ->add('intensiveCare',              TripleChoice::class,        array('required'=>$required, 'label'=>'rotavirus-form.intensiveCare', ))
            ->add('symptomDiarrhea',            TripleChoice::class,        array('required'=>$required, 'label'=>'rotavirus-form.symptomDiarrhea',         'hidden-child'=>'vaccineReceived'))
            ->add('symptomDiarrheaOnset',       DatePickerType::class,      array('required'=>$required, 'label'=>'rotavirus-form.symptomDiarrheaOnset',    'hidden-parent'=>'vaccineReceived', 'hidden-value'=>  TripleChoice::YES))
            ->add('symptomDiarrheaEpisodes',    null,                       array('required'=>$required, 'label'=>'rotavirus-form.symptomDiarrheaEpisodes', 'hidden-parent'=>'vaccineReceived', 'hidden-value'=>  TripleChoice::YES))
            ->add('symptomDiarrheaDuration',    null,                       array('required'=>$required, 'label'=>'rotavirus-form.symptomDiarrheaDuration', 'hidden-parent'=>'vaccineReceived', 'hidden-value'=>  TripleChoice::YES))
            ->add('symptomDiarrheaBloody',      TripleChoice::class,        array('required'=>$required, 'label'=>'rotavirus-form.symptomDiarrheaDuration', 'hidden-parent'=>'vaccineReceived', 'hidden-value'=>  TripleChoice::YES))

            ->add('symptomVomit',               TripleChoice::class,        array('required'=>$required, 'label'=>'rotavirus-form.symptomVomit',            'hidden-child'=>'symptomVomit'))
            ->add('symptomVomitEpisodes',       null,                       array('required'=>$required, 'label'=>'rotavirus-form.symptomVomitEpisodes',    'hidden-parent'=>'symptomVomit', 'hidden-value'=>  TripleChoice::YES))
            ->add('symptomVomitDuration',       null,                       array('required'=>$required, 'label'=>'rotavirus-form.symptomVomitDuration',    'hidden-parent'=>'symptomVomit', 'hidden-value'=>  TripleChoice::YES))

            ->add('symptomDehydration',         Dehydration::class,         array('required'=>$required, 'label'=>'rotavirus-form.symptomDehydration',       'hidden-child'=>'symptomDehydration'))
            ->add('rehydration',                TripleChoice::class,        array('required'=>$required, 'label'=>'rotavirus-form.rehydration',              'hidden-parent'=>'symptomDehydration','hidden-value'=> json_encode(array(Dehydration::SOME,Dehydration::SEVERE))))
            ->add('rehydrationType',            Rehydration::class,         array('required'=>$required, 'label'=>'rotavirus-form.rehydrationType',          'hidden-parent'=>'symptomDehydration','hidden-value'=> json_encode(array(Dehydration::SOME,Dehydration::SEVERE))))
            ->add('rehydrationOther',           null,                       array('required'=>$required, 'label'=>'rotavirus-form.rehydrationOther',         'hidden-parent'=>'symptomDehydration','hidden-value'=> json_encode(array(Dehydration::SOME,Dehydration::SEVERE))))

            ->add('vaccinationReceived',        VaccinationReceived::class, array(
                                                                                'required'=>$required,
                                                                                'label'=>'rotavirus-form.vaccinationReceived',
                                                                                'hidden-child'=>'vaccineReceived'))
            ->add('vaccinationType',            VaccinationType::class,     array(
                                                                                'required'=>$required,
                                                                                'label'=>'rotavirus-form.vaccinationType',
                                                                                'hidden-parent'=>'vaccineReceived',
                                                                                'hidden-value'=>json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY))))
            ->add('doses',                      ThreeDoses::class,          array(
                                                                                'required'=>$required, 'label'=>'rotavirus-form.doses',
                                                                                'hidden-parent'=>'vaccineReceived',
                                                                                'hidden-child'=>'vaccineReceivedDoses',
                                                                                'hidden-value'=>json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY))))
            ->add('firstVaccinationDose',       DatePickerType::class,      array(
                                                                                'required'=>$required,
                                                                                'label'=>'rotavirus-form.firstVaccinationDose',
                                                                                'hidden-parent'=>'vaccineReceivedDoses',
                                                                                'hidden-value'=> json_encode(array(ThreeDoses::ONE, ThreeDoses::TWO, ThreeDoses::THREE))))
            ->add('secondVaccinationDose',      DatePickerType::class,      array(
                                                                                'required'=>$required,
                                                                                'label'=>'rotavirus-form.secondVaccinationDose',
                                                                                'hidden-parent'=>'vaccineReceivedDoses',
                                                                                'hidden-value'=>json_encode(array(ThreeDoses::TWO, ThreeDoses::THREE))))
            ->add('thirdVaccinationDose',       DatePickerType::class,      array(
                                                                                'required'=>$required,
                                                                                'label'=>'rotavirus-form.thirdVaccinationDose',
                                                                                'hidden-parent'=>'vaccineReceivedDoses',
                                                                                'hidden-value'=>ThreeDoses::THREE))
            ->add('stoolCollected',             TripleChoice::class,        array('required'=>$required, 'label'=>'rotavirus-form.stoolCollected',  'hidden-child'=>'stoolCollected'))
            ->add('stoolId',                    null,                       array('required'=>$required, 'label'=>'rotavirus-form.stoolId',         'hidden-parent'=>'stoolCollected', 'hidden-value'=>  TripleChoice::YES))
            ->add('stoolCollectionDate',        DatePickerType::class,      array('required'=>$required, 'label'=>'rotavirus-form.stoolCollectionDate', 'hidden-parent'=>'stoolCollected', 'hidden-value'=>  TripleChoice::YES))
            ->add('dischargeOutcome',           DischargeOutcome::class,    array('required'=>false, 'label'=>'rotavirus-form.dischargeOutcome'))
            ->add('dischargeDate',              DatePickerType::class,      array('required'=>false, 'label'=>'rotavirus-form.dischargeDate'))
            ->add('dischargeClassification',    DischargeClassification::class, array('required'=>false,'label'=>'rotavirus-form.dischargeClassification'))
            ->add('dischargeClassOther',        null,                       array('required'=>false, 'label'=>'rotavirus-form.dischargeClassOther'))
            ->add('comment',                    null,                       array('required'=>false, 'label'=>'rotavirus-form.comment'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\RotaVirus',
            'validation_groups' => $this->validatorResolver,
        ));
    }
}
