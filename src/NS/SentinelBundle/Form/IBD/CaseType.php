<?php

namespace NS\SentinelBundle\Form\IBD;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Form\IBD\Types\CSFAppearance;
use NS\SentinelBundle\Form\IBD\Types\CXRAdditionalResult;
use NS\SentinelBundle\Form\IBD\Types\CXRResult;
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
use NS\SentinelBundle\Interfaces\SerializedSitesInterface;
use Symfony\Component\Form\AbstractType;
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
        $isPaho = $this->authChecker->isGranted('ROLE_AMR');

        $builder
            ->add('lastName',           null, ['required' => $required, 'label' => 'ibd-form.last-name'])
            ->add('firstName',          null, ['required' => $required, 'label' => 'ibd-form.first-name'])
            ->add('parentalName',       null, ['required' => $required, 'label' => 'ibd-form.parental-name'])
            ->add('dobKnown',           TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.date-of-birth-known', 'hidden-child' => 'dob', 'exclude_choices'=> ($isPaho ? [TripleChoice::UNKNOWN]:null)])
            ->add('birthdate',          DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.date-of-birth', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::YES, 'widget' => 'single_text'])
            ->add('dobYears',           null, ['required' => $required, 'label' => 'ibd-form.date-of-birth-years', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::NO])
            ->add('dobMonths',          null, ['required' => $required, 'label' => 'ibd-form.date-of-birth-months', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::NO])
            ->add('gender',             Gender::class, ['required' => $required, 'label' => 'ibd-form.gender'])
            ->add('district',           null, ['required' => $required, 'label' => 'ibd-form.district'])
            ->add('state',              null, ['required' => $required, 'label' => 'ibd-form.state'])
            ->add('caseId',             null, ['required' => true, 'label' => 'ibd-form.case-id'])
            ->add('admDate',            DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.adm-date'])
            ->add('admDx',              Diagnosis::class, ['required' => $required, 'label' => 'ibd-form.adm-dx', 'hidden-child' => 'admissionDiagnosis'])
            ->add('admDxOther',         null, ['required' => $required, 'label' => 'ibd-form.adm-dx-other', 'hidden-parent' => 'admissionDiagnosis', 'hidden-value' => Diagnosis::OTHER])
            ->add('onsetDate',          DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.onset-date'])
            ->add('antibiotics',        TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.antibiotics'])
            ->add('menSeizures',        TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-seizures'])
            ->add('menFever',           TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-fever'])
            ->add('menAltConscious',    TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-alt-conscious'])
            ->add('menInabilityFeed',   TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-inability-feed'])
            ->add('menNeckStiff',       TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-stiff-neck'])
            ->add('menRash',            TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-rash'])
            ->add('menFontanelleBulge', TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-fontanelle-bulge'])
            ->add('menLethargy',        TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.men-lethargy'])
            ->add('hibReceived',        VaccinationReceived::class, ['required' => $required, 'label' => 'ibd-form.hib-received', 'hidden-child' => 'hibReceived'])
            ->add('hibDoses',           FourDoses::class, ['required' => $required, 'label' => 'ibd-form.hib-doses', 'hidden-parent' => 'hibReceived', 'hidden-value' => json_encode([VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY])])
            ->add('hibMostRecentDose',  DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.hib-most-recent-dose', 'hidden-parent' => 'hibReceived', 'hidden-value' => json_encode([VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY])])
            ->add('pcvReceived',        VaccinationReceived::class, ['required' => $required, 'label' => 'ibd-form.pcv-received', 'hidden-child' => 'pcvReceived'])
            ->add('pcvDoses',           FourDoses::class, ['required' => $required, 'label' => 'ibd-form.pcv-doses', 'hidden-parent' => 'pcvReceived', 'hidden-value' => json_encode([VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY])])
            ->add('pcvType',            PCVType::class, ['required' => $required, 'label' => 'ibd-form.pcv-type', 'hidden-parent' => 'pcvReceived', 'hidden-value' => json_encode([VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY])])
            ->add('pcvMostRecentDose',  DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.pcv-most-recent-dose', 'hidden-parent' => 'pcvReceived', 'hidden-value' => json_encode([VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY])])
            ->add('meningReceived',     VaccinationReceived::class, ['required' => $required, 'label' => 'ibd-form.men-received', 'hidden-child' => 'meningReceived'])
            ->add('meningType',         VaccinationType::class, ['required' => $required, 'label' => 'ibd-form.men-type', 'hidden-parent' => 'meningReceived', 'hidden-value' => json_encode([VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY])])
            ->add('meningDate',         DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.meningMostRecentDose', 'hidden-parent' => 'meningReceived', 'hidden-value' => json_encode([VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY])])
            ->add('bloodCollected',     TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.blood-collected', 'hidden-child' => 'bloodCollected'])
            ->add('bloodCollectDate',   DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.blood-collect-date', 'hidden-parent' => 'bloodCollected', 'hidden-value' => TripleChoice::YES])
            ->add('bloodCollectTime',   TimeType::class, ['required' => $required, 'label' => 'ibd-form.blood-collect-time', 'hidden-parent' => 'bloodCollected', 'hidden-value' => TripleChoice::YES])
            ->add('csfCollected',       TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.csf-collected', 'hidden-child' => 'csfCollected','exclude_choices'=> ($isPaho ? [TripleChoice::UNKNOWN]:null)])
            ->add('csfCollectDate',     DatePickerType::class, ['required' => $required, 'label' => 'ibd-form.csf-collect-date', 'hidden-parent' => 'csfCollected', 'hidden-value' => TripleChoice::YES])
            ->add('csfCollectTime',     TimeType::class, ['required' => $required, 'label' => 'ibd-form.csf-collect-time', 'hidden-parent' => 'csfCollected', 'hidden-value' => TripleChoice::YES])
            ->add('csfAppearance',      CSFAppearance::class, ['required' => $required, 'label' => 'ibd-form.csf-appearance', 'hidden-parent' => 'csfCollected', 'hidden-value' => TripleChoice::YES])
            ->add('dischOutcome',       DischargeOutcome::class, ['required' => false, 'label' => 'ibd-form.discharge-outcome'])
            ->add('dischDx',            DischargeDiagnosis::class, ['required' => false, 'label' => 'ibd-form.discharge-diagnosis', 'hidden-child' => 'dischargeDiagnosis'])
            ->add('dischDxOther',       null, ['required' => false, 'label' => 'ibd-form.discharge-diagnosis-other', 'hidden-parent' => 'dischargeDiagnosis', 'hidden-value' => DischargeDiagnosis::OTHER])
            ->add('dischClass',         DischargeClassification::class, ['required' => false, 'label' => 'ibd-form.discharge-class','exclude_choices'=> ($isPaho ? [DischargeClassification::UNKNOWN,DischargeClassification::SUSPECT]:null)])
            ->add('comment',            null, ['required' => false, 'label' => 'ibd-form.comment']);
        ;

        if ($isPaho) {
            $builder
                ->add('bloodNumberOfSamples', null, ['required' => $required,'hidden'=>['parent' => 'bloodCollected', 'value' => TripleChoice::YES, 'label' => 'ibd-form.blood-number-of-samples']])
                ->add('pleuralFluidCollected', TripleChoice::class, ['required' => $required, 'hidden-child' => 'pleuralFluidCollected', 'label' => 'ibd-form.pleural-fluid-collected'])
                ->add('pleuralFluidCollectDate', DatePickerType::class, ['required' => $required, 'hidden'=>['parent' => 'pleuralFluidCollected', 'value' => TripleChoice::YES], 'label' => 'ibd-form.pleural-fluid-collection-date'])
                ->add('pleuralFluidCollectTime', TimeType::class, ['required' => $required,'hidden'=>['parent' => 'pleuralFluidCollected', 'value' => TripleChoice::YES], 'label' => 'ibd-form.pleural-fluid-collection-time'])
                ;
        } else {
            $builder
                ->add('otherSpecimenCollected', OtherSpecimen::class, ['required' => $required, 'label' => 'ibd-form.otherSpecimenCollected', 'hidden-child' => 'otherSpecimenCollected'])
                ->add('otherSpecimenOther', null, ['required' => $required, 'label' => 'ibd-form.otherSpecimenOther', 'hidden-parent' => 'otherSpecimenCollected', 'hidden-value'  => OtherSpecimen::OTHER]);
        }

        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this,'postSetData']);
    }

    public function postSetData(FormEvent $event)
    {
        $data     = $event->getData();
        $form     = $event->getForm();
        $required = ($form->getConfig()->getOption('method',false) === 'PUT');
        $country  = null;

        if ($data && $data->getCountry()) {
            $country = $data->getCountry();
        } elseif (!$this->siteSerializer->hasMultipleSites()) {
            $site    = $this->siteSerializer->getSite();
            $country = ($site instanceof Site) ? $site->getCountry() : null;
        }

        if (!$country || ($country instanceof Country && $country->getTracksPneumonia())) {
            $form->add('pneuDiffBreathe',       TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-diff-breathe'])
                ->add('pneuChestIndraw',        TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-chest-indraw'])
                ->add('pneuCough',              TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-cough'])
                ->add('pneuCyanosis',           TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-cyanosis'])
                ->add('pneuStridor',            TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-stridor'])
                ->add('pneuRespRate',           IntegerType::class, ['required' => $required, 'label' => 'ibd-form.pneu-resp-rate', 'attr' => ['min' => 10, 'max' => 100]])
                ->add('pneuVomit',              TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-vomit'])
                ->add('pneuHypothermia',        TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-hypothermia'])
                ->add('pneuMalnutrition',       TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.pneu-malnutrition'])
                ->add('cxrDone',                TripleChoice::class, ['required' => $required, 'label' => 'ibd-form.cxr-done', 'hidden-child' => 'cxrDone'])
                ->add('cxrResult',              CXRResult::class, ['required' => $required, 'label' => 'ibd-form.cxr-result', 'hidden-parent' => 'cxrDone', 'hidden-child' => 'cxrResult', 'hidden-value' => TripleChoice::YES])
                ->add('cxrAdditionalResult',    CXRAdditionalResult::class, ['required' => $required, 'label' => 'ibd-form.cxr-additional-result', 'hidden-parent' => 'cxrResult', 'hidden-value' => CXRResult::CONSISTENT])
            ;

            if ($this->authChecker->isGranted('ROLE_AMR')) {
                $form->add('pneuOxygenSaturation', null, ['required' => $required, 'label' => 'ibd-form.pneu-oxygen-level']);
            }
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IBD::class,
            'validation_groups' => $this->validatorResolver,
        ]);
    }
}
