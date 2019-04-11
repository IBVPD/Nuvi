<?php

namespace NS\SentinelBundle\Form\Meningitis;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Form\Meningitis\Types\CSFAppearance;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeOutcome;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use NS\SentinelBundle\Form\IBD\Types\PCVType;
use NS\SentinelBundle\Form\IBD\Types\VaccinationType;
use NS\SentinelBundle\Form\Types\FourDoses;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Form\ValidatorGroup\ValidatorGroupResolver;
use NS\SentinelBundle\Form\ValueObject\YearMonthType;
use NS\SentinelBundle\Interfaces\SerializedSitesInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CaseType extends AbstractType
{
    /** @var SerializedSitesInterface */
    private $siteSerializer;

    /** @var ValidatorGroupResolver */
    private $validatorResolver;

    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    public function __construct(SerializedSitesInterface $siteSerializer, ValidatorGroupResolver $resolver, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->siteSerializer = $siteSerializer;
        $this->validatorResolver = $resolver;
        $this->authChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $required = (isset($options['method']) && $options['method'] === 'PUT');

        $builder
            ->add('parentalName',       null, ['required' => $required, 'label' => 'ibd-form.parental-name', 'attr' => ['autocomplete' => 'off']])
            ->add('birthdate',          DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.date-of-birth', 'hidden' => ['parent' => 'dobKnown', 'value' => TripleChoice::YES], 'widget' => 'single_text'])
            ->add('dobYearMonths',      YearMonthType::class, [ 'required' => $required, 'hidden' => ['parent'=>'dobKnown', 'value' => TripleChoice::NO]])
            ->add('district',           null, ['required' => $required, 'label' => 'ibd-form.district'])
            ->add('state',              null, ['required' => $required, 'label' => 'ibd-form.state'])
            ->add('caseId',             null, ['required' => true, 'label' => 'ibd-form.case-id', 'property_path' => 'case_id'])
            ->add('onsetDate',          DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.onset-date','property_path'=>'onset_date'])
            ->add('antibiotics',        TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.antibiotics'])
            ->add('antibiotic_name',    TextType::class, ['required' => false, 'label' => 'ibd-form.antibiotic_name', 'hidden' => ['parent' => 'antibiotics', 'value' => TripleChoice::YES]])
            ->add('menSeizures',        TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-seizures'])
            ->add('menFever',           TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-fever'])
            ->add('menAltConscious',    TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-alt-conscious'])
            ->add('menInabilityFeed',   TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-inability-feed'])
            ->add('menNeckStiff',       TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-stiff-neck'])
            ->add('menRash',            TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-rash'])
            ->add('menFontanelleBulge', TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-fontanelle-bulge'])
            ->add('menLethargy',        TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-lethargy'])
            ->add('hibDoses',           FourDoses::class, ['required' => $required, 'label' => 'ibd-form.hib-doses', 'hidden' => ['parent' => 'hibReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('hibMostRecentDose',  DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.hib-most-recent-dose', 'hidden' => ['parent' => 'hibReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('pcvDoses',           FourDoses::class, ['required' => $required, 'label' => 'ibd-form.pcv-doses', 'hidden' => ['parent' => 'pcvReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('pcvType',            PCVType::class, ['required' => $required, 'label' => 'ibd-form.pcv-type', 'hidden' => ['parent' => 'pcvReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('pcvMostRecentDose',  DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.pcv-most-recent-dose', 'hidden' => ['parent' => 'pcvReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('bloodCollected',     TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.blood-collected'])
            ->add('bloodCollectDate',   DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.blood-collect-date', 'hidden' => ['parent' => 'bloodCollected', 'value' => TripleChoice::YES]])
            ->add('bloodCollectTime',   TimeType::class, ['required' => $required, 'label' => 'ibd-form.blood-collect-time', 'hidden' => ['parent' => 'bloodCollected', 'value' => TripleChoice::YES]])
            ->add('csfCollectDate',     DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.csf-collect-date', 'hidden' => ['parent' => 'csfCollected', 'value' => TripleChoice::YES]])
            ->add('csfCollectTime',     TimeType::class, ['required' => $required, 'label' => 'ibd-form.csf-collect-time', 'hidden' => ['parent' => 'csfCollected', 'value' => TripleChoice::YES]])
            ->add('csfAppearance',      CSFAppearance::class, ['required' => $required, 'label' => 'ibd-form.csf-appearance', 'hidden' => ['parent' => 'csfCollected', 'value' => TripleChoice::YES]])
            ->add('dischOutcome',       DischargeOutcome::class, ['required' => false, 'label' => 'ibd-form.discharge-outcome'])
            ->add('dischDxOther',       null, ['required' => false, 'label' => 'ibd-form.discharge-diagnosis-other', 'hidden' => ['parent' => 'dischDx', 'value' => DischargeDiagnosis::OTHER]])
            ->add('comment',            null, ['required' => false, 'label' => 'ibd-form.comment']);

        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this,'postSetData']);
    }

    public function postSetData(FormEvent $event)
    {
        $data     = $event->getData();
        $form     = $event->getForm();
        $required = ($form->getConfig()->getOption('method', false) === 'PUT');
        $region = $country  = null;

        if ($data && $data->getCountry()) {
            $region = $data->getRegion();
        } elseif (!$this->siteSerializer->hasMultipleSites()) {
            $site    = $this->siteSerializer->getSite();
            $country = ($site instanceof Site) ? $site->getCountry() : null;
            $region  = ($country instanceof Country) ? $country->getRegion(): null;
        }

        $isPaho = ($region && $region->getCode() === 'AMR') || $this->authChecker->isGranted('ROLE_AMR');

        $form
            ->add('lastName', null, ['required' => $required || $isPaho, 'label' => 'ibd-form.last-name', 'attr' => ['autocomplete' => 'off']])
            ->add('firstName', null, ['required' => $required || $isPaho, 'label' => 'ibd-form.first-name', 'attr' => ['autocomplete' => 'off']])
            ->add('dobKnown', TripleChoice::class, ['required' => $required || $isPaho, 'label' => 'ibd-form.date-of-birth-known', 'exclude_choices' => $isPaho ? [TripleChoice::UNKNOWN] : null])
            ->add('gender', Gender::class, ['required' => $required || $isPaho, 'label' => 'ibd-form.gender'])
            ->add('admDx',Diagnosis::class, [
                'required' => $required || $isPaho,
                'label' => 'ibd-form.adm-dx',
                'property_path' => 'adm_dx',
                'exclude_choices' => $isPaho ? [Diagnosis::SUSPECTED_SEVERE_PNEUMONIA, Diagnosis::UNKNOWN, Diagnosis::OTHER, Diagnosis::SUSPECTED_SEPSIS,]:[],
            ])
            ->add('admDxOther',null, ['required' => $required, 'label' => 'ibd-form.adm-dx-other', 'hidden' => ['parent' => 'admDx', 'value' => Diagnosis::OTHER]])
            ->add('admDate', DatePickerType::class, ['required' => $required || $isPaho, 'label' => 'ibd-form.adm-date', 'property_path' => 'adm_date'])
            ->add('csfCollected', TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.csf-collected', 'exclude_choices' => $isPaho ? [TripleChoice::UNKNOWN] : null])
            ->add('dischDx',    DischargeDiagnosis::class, ['required' => false, 'label' => 'ibd-form.discharge-diagnosis', 'exclude_choices' => $isPaho ? [DischargeDiagnosis::UNKNOWN, DischargeDiagnosis::SEPSIS]:null,])
            ->add('dischClass', DischargeClassification::class, ['required' => false, 'label' => 'ibd-form.discharge-class', 'exclude_choices' => $isPaho ? [DischargeClassification::UNKNOWN, DischargeClassification::SUSPECT] : null])
            ->add('hibReceived', VaccinationReceived::class, ['required' => $required || $isPaho, 'label' => 'ibd-form.hib-received','property_path'=>'hib_received'])
            ->add('pcvReceived', VaccinationReceived::class, ['required' => $required || $isPaho, 'label' => 'ibd-form.pcv-received','property_path'=>'pcv_received'])
            ->add('meningReceived', VaccinationReceived::class, ['required' => $required || $isPaho, 'label' => 'ibd-form.men-received','property_path' => 'mening_received'])
            ->add('meningType', VaccinationType::class, [
                'required' => $required,
                'label' => 'ibd-form.men-type',
                'hidden' => ['parent' => 'meningReceived', 'value' => $isPaho ? [VaccinationReceived::YES_CARD]:[VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY] ],
                'exclude_choices' => $isPaho ? [VaccinationType::MEN_AFR_VAC, VaccinationType::ACW135]:[VaccinationType::B,VaccinationType::C],
            ])
            ->add('meningDate', DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.meningMostRecentDose', 'property_path' => 'mening_date', 'hidden' => ['parent' => 'meningReceived', 'value' => $isPaho ? [VaccinationReceived::YES_CARD]:[VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY] ]]);

        if ($isPaho) {
            $form
                ->add('menIrritability',    TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-irritability'])
                ->add('menVomit',           TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-vomit'])
                ->add('menMalnutrition',    TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-malnutrition'])
                ->add('bloodNumberOfSamples', ChoiceType::class, ['required' => $required, 'label' => 'ibd-form.blood-number-of-samples', 'choices' => ['One' => 1, 'Two' => 2], 'placeholder' => 'Please Select...', 'hidden' => ['parent' => 'bloodCollected', 'value' => TripleChoice::YES]])
                ->add('bloodSecondCollectDate',   DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.blood-collect-date-second-sample', 'hidden' => ['parent' => 'bloodNumberOfSamples', 'value' => 2]])
                ->add('bloodSecondCollectTime',   TimeType::class, ['required' => $required, 'label' => 'ibd-form.blood-collect-time-second-sample', 'hidden' => ['parent' => 'bloodNumberOfSamples', 'value' => 2]]);
        } else {
            $form
                ->add('otherSpecimenCollected', OtherSpecimen::class, ['required' => $required, 'label' => 'ibd-form.otherSpecimenCollected'])
                ->add('otherSpecimenOther', null, ['required' => $required, 'label' => 'ibd-form.otherSpecimenOther', 'hidden' => ['parent' => 'otherSpecimenCollected', 'value' => OtherSpecimen::OTHER]]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Meningitis::class,
            'validation_groups' => $this->validatorResolver,
        ]);
    }
}
