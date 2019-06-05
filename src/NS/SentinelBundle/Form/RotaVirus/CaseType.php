<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Form\RotaVirus\Types\Dehydration;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeClassification;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome;
use NS\SentinelBundle\Form\RotaVirus\Types\Rehydration;
use NS\SentinelBundle\Form\RotaVirus\Types\VaccinationType;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\ThreeDoses;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Form\ValidatorGroup\ValidatorGroupResolver;
use NS\SentinelBundle\Form\ValueObject\YearMonthType;
use NS\SentinelBundle\Interfaces\SerializedSitesInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CaseType extends AbstractType
{
    /** @var ValidatorGroupResolver */
    private $validatorResolver;

    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    /** @var SerializedSitesInterface */
    private $siteSerializer;

    public function __construct(ValidatorGroupResolver $validatorResolver, AuthorizationCheckerInterface $authorizationChecker, SerializedSitesInterface $siteSerializer)
    {
        $this->validatorResolver = $validatorResolver;
        $this->authChecker = $authorizationChecker;
        $this->siteSerializer = $siteSerializer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $required = (isset($options['method']) && $options['method'] === 'PUT');
        $isPaho = $this->authChecker->isGranted('ROLE_AMR');

        $builder
            ->add('lastName',                   null,               ['required' => $required || $isPaho, 'label' => 'rotavirus-form.last-name', 'attr' => ['autocomplete' => 'off']])
            ->add('firstName',                  null,               ['required' => $required || $isPaho, 'label' => 'rotavirus-form.first-name', 'attr' => ['autocomplete' => 'off']])
            ->add('parentalName',               null,               ['required' => $required, 'label' => 'rotavirus-form.parental-name', 'attr' => ['autocomplete' => 'off']])
            ->add('caseId',                     null,               ['required' => true,      'label' => 'rotavirus-form.caseId', 'property_path' => 'case_id'])
            ->add('gender',                     Gender::class,                  ['required' => $required || $isPaho, 'label' => 'rotavirus-form.gender'])
            ->add('dobKnown',                   TripleChoice::class,            ['required' => $required || $isPaho, 'label' => 'ibd-form.date-of-birth-known'])
            ->add('birthdate',                  DatePickerType::class,          ['required' => $required, 'label' => 'ibd-form.date-of-birth', 'hidden' => ['parent' => 'dobKnown', 'value' => TripleChoice::YES], 'widget' => 'single_text'])
            ->add('dobYearMonths',              YearMonthType::class,           ['required' => $required, 'hidden' => ['parent'=>'dobKnown', 'value' => TripleChoice::NO]])
            ->add('district',                   null,                           ['required' => $required, 'label' => 'rotavirus-form.district'])
            ->add('state',                      null,                           ['required' => $required, 'label' => 'rotavirus-form.state'])
            ->add('admDate',                    DatePickerType::class,          ['required' => $required || $isPaho, 'label' => 'rotavirus-form.admissionDate', 'property_path' => 'adm_date'])
            ->add('intensiveCare',              TripleChoice::class,            ['required' => $required, 'label' => 'rotavirus-form.intensiveCare',])
            ->add('symptomDiarrhea',            TripleChoice::class,            ['required' => $required, 'label' => 'rotavirus-form.symptomDiarrhea'])
            ->add('symptomDiarrheaOnset',       DatePickerType::class,          ['required' => $required, 'label' => 'rotavirus-form.symptomDiarrheaOnset', 'hidden' => ['parent' => 'symptomDiarrhea', 'value' => TripleChoice::YES]])
            ->add('symptomDiarrheaEpisodes',    null,                           ['required' => $required, 'label' => 'rotavirus-form.symptomDiarrheaEpisodes', 'hidden' => ['parent' => 'symptomDiarrhea', 'value' => TripleChoice::YES]])
            ->add('symptomDiarrheaDuration',    null,                           ['required' => $required, 'label' => 'rotavirus-form.symptomDiarrheaDuration', 'hidden' => ['parent' => 'symptomDiarrhea', 'value' => TripleChoice::YES]])
            ->add('symptomVomit',               TripleChoice::class,            ['required' => $required, 'label' => 'rotavirus-form.symptomVomit'])
            ->add('symptomVomitEpisodes',       null,                           ['required' => $required, 'label' => 'rotavirus-form.symptomVomitEpisodes', 'hidden' => ['parent' => 'symptomVomit', 'value' => TripleChoice::YES]])
            ->add('symptomVomitDuration',       null,                           ['required' => $required, 'label' => 'rotavirus-form.symptomVomitDuration', 'hidden' => ['parent' => 'symptomVomit', 'value' => TripleChoice::YES]])
            ->add('symptomDehydration',         Dehydration::class,             ['required' => $required, 'label' => 'rotavirus-form.symptomDehydration'])
            ->add('rehydration',                TripleChoice::class,            ['required' => $required, 'label' => 'rotavirus-form.rehydration', 'hidden' => ['parent' => 'symptomDehydration', 'value' => [Dehydration::SOME, Dehydration::SEVERE, Dehydration::MODERATE]]])
            ->add('rehydrationType',            Rehydration::class,             ['required' => $required, 'label' => 'rotavirus-form.rehydrationType', 'hidden' => ['parent' => 'symptomDehydration', 'value' => [Dehydration::SOME, Dehydration::SEVERE, Dehydration::MODERATE]]])
            ->add('rehydrationOther',           null,                           ['required' => $required, 'label' => 'rotavirus-form.rehydrationOther', 'hidden' => ['parent' => 'symptomDehydration', 'value' => [Dehydration::SOME, Dehydration::SEVERE, Dehydration::MODERATE]]])
            ->add('vaccinationReceived',        VaccinationReceived::class,     ['required' => $required, 'label' => 'rotavirus-form.vaccinationReceived'])
            ->add('vaccinationType',            VaccinationType::class,         ['required' => $required, 'label' => 'rotavirus-form.vaccinationType', 'hidden' => ['parent' => 'vaccinationReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('doses',                      ThreeDoses::class,              ['required' => $required, 'label' => 'rotavirus-form.doses', 'hidden' => ['parent' => 'vaccinationReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('firstVaccinationDose',       DatePickerType::class,          ['required' => $required, 'label' => 'rotavirus-form.firstVaccinationDose', 'hidden' => ['parent' => 'doses', 'value' => [ThreeDoses::ONE, ThreeDoses::TWO, ThreeDoses::THREE]]])
            ->add('secondVaccinationDose',      DatePickerType::class,          ['required' => $required, 'label' => 'rotavirus-form.secondVaccinationDose', 'hidden' => ['parent' => 'doses', 'value' => [ThreeDoses::TWO, ThreeDoses::THREE]]])
            ->add('thirdVaccinationDose',       DatePickerType::class,          ['required' => $required, 'label' => 'rotavirus-form.thirdVaccinationDose', 'hidden' => ['parent' => 'doses', 'value' => ThreeDoses::THREE]])
            ->add('stoolCollected',             TripleChoice::class,            ['required' => $required, 'label' => 'rotavirus-form.stoolCollected'])
            ->add('stoolId',                    null,                           ['required' => $required, 'label' => 'rotavirus-form.stoolId', 'hidden' => ['parent' => 'stoolCollected', 'value' => TripleChoice::YES]])
            ->add('stoolCollectionDate',        DatePickerType::class,          ['required' => $required, 'label' => 'rotavirus-form.stoolCollectionDate', 'hidden' => ['parent' => 'stoolCollected', 'value' => TripleChoice::YES]])
            ->add('dischargeOutcome',           DischargeOutcome::class,        ['required' => false, 'label' => 'rotavirus-form.dischargeOutcome'])
            ->add('dischargeDate',              DatePickerType::class,          ['required' => false, 'label' => 'rotavirus-form.dischargeDate'])
            ->add('dischargeClassification',    DischargeClassification::class, ['required' => false, 'label' => 'rotavirus-form.dischargeClassification'])
            ->add('dischargeClassOther',        null,                           ['required' => false, 'label' => 'rotavirus-form.dischargeClassOther'])
            ->add('comment',                    null,                           ['required' => false, 'label' => 'rotavirus-form.comment'])
        ;

        $builder->addEventListener(FormEvents::POST_SET_DATA,[$this,'postSetData']);
    }

    public function postSetData(FormEvent $event): void
    {
        /** @var RotaVirus $data */
        $data = $event->getData();
        $add = ($data && $data->getSite() && $data->getSite()->isTacPhase2());
        if (!$add && !$this->siteSerializer->hasMultipleSites()) {
            $site = $this->siteSerializer->getSite();
            $add = ($site instanceof Site && $site->isTacPhase2());
        }

        if ($add) {
            $form = $event->getForm();
            $form->add('symptomDiarrheaBloody', TripleChoice::class, ['required' => true, 'label' => 'rotavirus-form.symptomDiarrheaBloody']);
            $form->remove('symptomDiarrhea');
            $form->add('symptomDiarrhea', TripleChoice::class, ['required' => true, 'label' => 'rotavirus-form.symptomDiarrhea']);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RotaVirus::class,
            'validation_groups' => $this->validatorResolver,
        ]);
    }
}
