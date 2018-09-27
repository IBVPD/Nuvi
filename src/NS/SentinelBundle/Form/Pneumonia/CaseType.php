<?php

namespace NS\SentinelBundle\Form\Pneumonia;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Form\Pneumonia\Types\CXRAdditionalResult;
use NS\SentinelBundle\Form\Pneumonia\Types\CXRResult;
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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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

    /**
     * CaseType constructor.
     * @param SerializedSitesInterface $siteSerializer
     * @param ValidatorGroupResolver $resolver
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(SerializedSitesInterface $siteSerializer, ValidatorGroupResolver $resolver, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->siteSerializer = $siteSerializer;
        $this->validatorResolver = $resolver;
        $this->authChecker = $authorizationChecker;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $required = (isset($options['method']) && $options['method'] == 'PUT');

        $builder
            ->add('parentalName',       null, ['required' => $required, 'label' => 'ibd-form.parental-name', 'attr' => ['autocomplete' => 'off']])
            ->add('birthdate',          DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.date-of-birth', 'hidden' => ['parent' => 'dobKnown', 'value' => TripleChoice::YES], 'widget' => 'single_text'])
            ->add('dobYearMonths',      YearMonthType::class, [ 'required' => $required, 'hidden' => ['parent'=>'dobKnown', 'value' => TripleChoice::NO]])
            ->add('district',           null, ['required' => $required, 'label' => 'ibd-form.district'])
            ->add('state',              null, ['required' => $required, 'label' => 'ibd-form.state'])
            ->add('caseId',             null, ['required' => true, 'label' => 'ibd-form.case-id', 'property_path' => 'case_id'])
            ->add('admDxOther',         null, ['required' => $required, 'label' => 'ibd-form.adm-dx-other', 'hidden' => ['parent' => 'admDx', 'value' => Diagnosis::OTHER]])
            ->add('onsetDate',          DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.onset-date','property_path'=>'onset_date'])
            ->add('antibiotics',        TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.antibiotics'])
            ->add('hibDoses',           FourDoses::class, ['required' => $required, 'label' => 'ibd-form.hib-doses', 'hidden' => ['parent' => 'hibReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('hibMostRecentDose',  DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.hib-most-recent-dose', 'hidden' => ['parent' => 'hibReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('pcvDoses',           FourDoses::class, ['required' => $required, 'label' => 'ibd-form.pcv-doses', 'hidden' => ['parent' => 'pcvReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('pcvType',            PCVType::class, ['required' => $required, 'label' => 'ibd-form.pcv-type', 'hidden' => ['parent' => 'pcvReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('pcvMostRecentDose',  DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.pcv-most-recent-dose', 'hidden' => ['parent' => 'pcvReceived', 'value' => [VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY]]])
            ->add('bloodCollected',     TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.blood-collected'])
            ->add('bloodCollectDate',   DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.blood-collect-date', 'hidden' => ['parent' => 'bloodCollected', 'value' => TripleChoice::YES]])
            ->add('bloodCollectTime',   TimeType::class, ['required' => $required, 'label' => 'ibd-form.blood-collect-time', 'hidden' => ['parent' => 'bloodCollected', 'value' => TripleChoice::YES]])
            ->add('dischOutcome',       DischargeOutcome::class, ['required' => false, 'label' => 'ibd-form.discharge-outcome'])
            ->add('dischDxOther',       null, ['required' => false, 'label' => 'ibd-form.discharge-diagnosis-other', 'hidden' => ['parent' => 'dischDx', 'value' => DischargeDiagnosis::OTHER]])
            ->add('comment',            null, ['required' => false, 'label' => 'ibd-form.comment']);
        ;

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
            $country = $data->getCountry();
        } elseif (!$this->siteSerializer->hasMultipleSites()) {
            $site    = $this->siteSerializer->getSite();
            $country = ($site instanceof Site) ? $site->getCountry() : null;
            $region  = ($country instanceof Country) ? $country->getRegion(): null;
        }

        $isPaho = (($region && $region->getCode() === 'AMR') || $this->authChecker->isGranted('ROLE_AMR'));

        if (!$country || ($country instanceof Country && $country->getTracksPneumonia())) {
            $form
                ->add('pneuDiffBreathe',     TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-diff-breathe'])
                ->add('pneuChestIndraw',     TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-chest-indraw'])
                ->add('pneuCough',           TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-cough'])
                ->add('pneuCyanosis',        TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-cyanosis'])
                ->add('pneuStridor',         TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-stridor'])
                ->add('pneuRespRate',        IntegerType::class, ['required' => false, 'label' => 'ibd-form.pneu-resp-rate', 'attr' => ['min' => 10, 'max' => 120],'property_path' => 'pneu_resp_rate'])
                ->add('pneuVomit',           TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-vomit'])
                ->add('pneuHypothermia',     TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-hypothermia'])
                ->add('pneuMalnutrition',    TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-malnutrition'])
                ->add('pneuFever',           TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-fever'])
                ->add('cxrDone',             TripleChoice::class, ['required' => $isPaho || $required, 'label' => 'ibd-form.cxr-done'])
                ->add('cxrResult',           CXRResult::class, ['required' => $required, 'label' => 'ibd-form.cxr-result', 'hidden' => ['parent' => 'cxrDone', 'value' => TripleChoice::YES]]);

            if ($isPaho) {
                $form
                    ->add('cxrAdditionalResult', CXRAdditionalResult::class, [
                        'required' => $required,
                        'label' => 'ibd-form.cxr-additional-result',
                        'expanded' => true,
                        'hidden' => [
                            'parent' => 'cxrResult',
                            'value' => [
                                CXRResult::CONSISTENT,
                                CXRResult::VIRAL_BACTERIAL,
                                CXRResult::VIRAL_PNEUMONIA,
                                CXRResult::INCONCLUSIVE,
                                CXRResult::OTHER,
                            ]
                        ]
                    ])
                    ->add('pneuOxygenSaturation', IntegerType::class, ['required' => false, 'label' => 'ibd-form.pneu-oxygen-level', 'property_path' => 'pneu_oxygen_saturation', 'attr' => ['min' => 80, 'max' => 100]]);
            }
        }

        $form
            ->add('lastName', null, ['required' => $required || $isPaho, 'label' => 'ibd-form.last-name', 'attr' => ['autocomplete' => 'off']])
            ->add('firstName', null, ['required' => $required || $isPaho, 'label' => 'ibd-form.first-name', 'attr' => ['autocomplete' => 'off']])
            ->add('dobKnown', TripleChoice::class, ['required' => $required || $isPaho, 'label' => 'ibd-form.date-of-birth-known', 'exclude_choices' => ($isPaho ? [TripleChoice::UNKNOWN] : null)])
            ->add('gender', Gender::class, ['required' => $required || $isPaho, 'label' => 'ibd-form.gender'])
            ->add('admDx',Diagnosis::class, [
                'required' => $required || $isPaho,
                'label' => 'ibd-form.adm-dx',
                'property_path' => 'adm_dx',
                'exclude_choices' => $isPaho ? [Diagnosis::SUSPECTED_SEVERE_PNEUMONIA, Diagnosis::UNKNOWN, Diagnosis::OTHER, Diagnosis::SUSPECTED_SEPSIS,]:[],
            ])
            ->add('admDate', DatePickerType::class, ['required' => $required || $isPaho, 'label' => 'ibd-form.adm-date', 'property_path' => 'adm_date'])
            ->add('dischDx',    DischargeDiagnosis::class, ['required' => false, 'label' => 'ibd-form.discharge-diagnosis', 'exclude_choices' => $isPaho ? [DischargeDiagnosis::UNKNOWN]:null,])
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
                ->add('bloodNumberOfSamples', ChoiceType::class, ['required' => $required, 'label' => 'ibd-form.blood-number-of-samples', 'choices' => ['One' => 1, 'Two' => 2], 'placeholder' => 'Please Select...', 'hidden' => ['parent' => 'bloodCollected', 'value' => TripleChoice::YES]])
                ->add('bloodSecondCollectDate',   DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.blood-collect-date-second-sample', 'hidden' => ['parent' => 'bloodNumberOfSamples', 'value' => 2]])
                ->add('bloodSecondCollectTime',   TimeType::class, ['required' => $required, 'label' => 'ibd-form.blood-collect-time-second-sample', 'hidden' => ['parent' => 'bloodNumberOfSamples', 'value' => 2]])
                ->add('pleuralFluidCollected', TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pleural-fluid-collected'])
                ->add('pleuralFluidCollectDate', DatePickerType::class, ['required' => $required, 'hidden' => ['parent' => 'pleuralFluidCollected', 'value' => TripleChoice::YES], 'label' => 'ibd-form.pleural-fluid-collection-date'])
                ->add('pleuralFluidCollectTime', TimeType::class, ['required' => $required, 'hidden' => ['parent' => 'pleuralFluidCollected', 'value' => TripleChoice::YES], 'label' => 'ibd-form.pleural-fluid-collection-time']);
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
            'data_class' => Pneumonia::class,
            'validation_groups' => $this->validatorResolver,
        ]);
    }
}
