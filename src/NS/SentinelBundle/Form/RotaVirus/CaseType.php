<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Entity\RotaVirus;
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
            ->add('lastName',                   null,               ['required'=>$required, 'label'=>'rotavirus-form.last-name'])
            ->add('firstName',                  null,               ['required'=>$required, 'label'=>'rotavirus-form.first-name'])
            ->add('parentalName',               null,               ['required'=>$required, 'label'=>'rotavirus-form.parental-name'])
            ->add('caseId',                     null,               ['required'=>true,      'label'=>'rotavirus-form.caseId','property_path' => 'case_id'])
            ->add('gender',                     Gender::class,              ['required'=>$required, 'label'=>'rotavirus-form.gender'])
            ->add('dobKnown',                   TripleChoice::class,        ['required'=>$required, 'label' => 'ibd-form.date-of-birth-known', 'hidden-child' => 'dob'])
            ->add('birthdate',                  DatePickerType::class,      ['required'=>$required, 'label' => 'ibd-form.date-of-birth', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::YES, 'widget' => 'single_text'])
            ->add('dobYears',                   null,                       ['required'=>$required, 'label' => 'ibd-form.date-of-birth-years', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::NO])
            ->add('dobMonths',                  null,                       ['required'=>$required, 'label' => 'ibd-form.date-of-birth-months', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::NO])
            ->add('district',                   null,                       ['required'=>$required, 'label'=>'rotavirus-form.district'])
            ->add('state',                      null,                       ['required'=>$required, 'label'=>'rotavirus-form.state'])
            ->add('admDate',                    DatePickerType::class,      ['required'=>$required, 'label'=>'rotavirus-form.admissionDate','property_path' => 'adm_date'])

            ->add('intensiveCare',              TripleChoice::class,        ['required'=>$required, 'label'=>'rotavirus-form.intensiveCare',])
            ->add('symptomDiarrhea',            TripleChoice::class,        ['required'=>$required, 'label'=>'rotavirus-form.symptomDiarrhea',         'hidden-child'=>'vaccineReceived'])
            ->add('symptomDiarrheaOnset',       DatePickerType::class,      ['required'=>$required, 'label'=>'rotavirus-form.symptomDiarrheaOnset',    'hidden-parent'=>'vaccineReceived', 'hidden-value'=>  TripleChoice::YES])
            ->add('symptomDiarrheaEpisodes',    null,                       ['required'=>$required, 'label'=>'rotavirus-form.symptomDiarrheaEpisodes', 'hidden-parent'=>'vaccineReceived', 'hidden-value'=>  TripleChoice::YES])
            ->add('symptomDiarrheaDuration',    null,                       ['required'=>$required, 'label'=>'rotavirus-form.symptomDiarrheaDuration', 'hidden-parent'=>'vaccineReceived', 'hidden-value'=>  TripleChoice::YES])
            ->add('symptomDiarrheaBloody',      TripleChoice::class,        ['required'=>$required, 'label'=>'rotavirus-form.symptomDiarrheaDuration', 'hidden-parent'=>'vaccineReceived', 'hidden-value'=>  TripleChoice::YES])

            ->add('symptomVomit',               TripleChoice::class,        ['required'=>$required, 'label'=>'rotavirus-form.symptomVomit',            'hidden-child'=>'symptomVomit'])
            ->add('symptomVomitEpisodes',       null,                       ['required'=>$required, 'label'=>'rotavirus-form.symptomVomitEpisodes',    'hidden-parent'=>'symptomVomit', 'hidden-value'=>  TripleChoice::YES])
            ->add('symptomVomitDuration',       null,                       ['required'=>$required, 'label'=>'rotavirus-form.symptomVomitDuration',    'hidden-parent'=>'symptomVomit', 'hidden-value'=>  TripleChoice::YES])

            ->add('symptomDehydration',         Dehydration::class,         ['required'=>$required, 'label'=>'rotavirus-form.symptomDehydration',       'hidden-child'=>'symptomDehydration'])
            ->add('rehydration',                TripleChoice::class,        ['required'=>$required, 'label'=>'rotavirus-form.rehydration',              'hidden-parent'=>'symptomDehydration','hidden-value'=> json_encode([Dehydration::SOME,Dehydration::SEVERE])])
            ->add('rehydrationType',            Rehydration::class,         ['required'=>$required, 'label'=>'rotavirus-form.rehydrationType',          'hidden-parent'=>'symptomDehydration','hidden-value'=> json_encode([Dehydration::SOME,Dehydration::SEVERE])])
            ->add('rehydrationOther',           null,                       ['required'=>$required, 'label'=>'rotavirus-form.rehydrationOther',         'hidden-parent'=>'symptomDehydration','hidden-value'=> json_encode([Dehydration::SOME,Dehydration::SEVERE])])

            ->add('vaccinationReceived',        VaccinationReceived::class, [
                                                                                'required'=>$required,
                                                                                'label'=>'rotavirus-form.vaccinationReceived',
                                                                                'hidden-child'=>'vaccineReceived'])
            ->add('vaccinationType',            VaccinationType::class,     [
                                                                                'required'=>$required,
                                                                                'label'=>'rotavirus-form.vaccinationType',
                                                                                'hidden-parent'=>'vaccineReceived',
                                                                                'hidden-value'=>json_encode([VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY])])
            ->add('doses',                      ThreeDoses::class,          [
                                                                                'required'=>$required, 'label'=>'rotavirus-form.doses',
                                                                                'hidden-parent'=>'vaccineReceived',
                                                                                'hidden-child'=>'vaccineReceivedDoses',
                                                                                'hidden-value'=>json_encode([VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY])])
            ->add('firstVaccinationDose',       DatePickerType::class,      [
                                                                                'required'=>$required,
                                                                                'label'=>'rotavirus-form.firstVaccinationDose',
                                                                                'hidden-parent'=>'vaccineReceivedDoses',
                                                                                'hidden-value'=> json_encode([ThreeDoses::ONE, ThreeDoses::TWO, ThreeDoses::THREE])])
            ->add('secondVaccinationDose',      DatePickerType::class,      [
                                                                                'required'=>$required,
                                                                                'label'=>'rotavirus-form.secondVaccinationDose',
                                                                                'hidden-parent'=>'vaccineReceivedDoses',
                                                                                'hidden-value'=>json_encode([ThreeDoses::TWO, ThreeDoses::THREE])])
            ->add('thirdVaccinationDose',       DatePickerType::class,      [
                                                                                'required'=>$required,
                                                                                'label'=>'rotavirus-form.thirdVaccinationDose',
                                                                                'hidden-parent'=>'vaccineReceivedDoses',
                                                                                'hidden-value'=>ThreeDoses::THREE])
            ->add('stoolCollected',             TripleChoice::class,        ['required'=>$required, 'label'=>'rotavirus-form.stoolCollected',  'hidden-child'=>'stoolCollected'])
            ->add('stoolId',                    null,                       ['required'=>$required, 'label'=>'rotavirus-form.stoolId',         'hidden-parent'=>'stoolCollected', 'hidden-value'=>  TripleChoice::YES])
            ->add('stoolCollectionDate',        DatePickerType::class,      ['required'=>$required, 'label'=>'rotavirus-form.stoolCollectionDate', 'hidden-parent'=>'stoolCollected', 'hidden-value'=>  TripleChoice::YES])
            ->add('dischargeOutcome',           DischargeOutcome::class,    ['required'=>false, 'label'=>'rotavirus-form.dischargeOutcome'])
            ->add('dischargeDate',              DatePickerType::class,      ['required'=>false, 'label'=>'rotavirus-form.dischargeDate'])
            ->add('dischargeClassification',    DischargeClassification::class, ['required'=>false,'label'=>'rotavirus-form.dischargeClassification'])
            ->add('dischargeClassOther',        null,                       ['required'=>false, 'label'=>'rotavirus-form.dischargeClassOther'])
            ->add('comment',                    null,                       ['required'=>false, 'label'=>'rotavirus-form.comment'])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RotaVirus::class,
            'validation_groups' => $this->validatorResolver,
        ]);
    }
}
