<?php

namespace NS\SentinelBundle\Form\IBD;

use \NS\SentinelBundle\Entity\Country;
use \NS\SentinelBundle\Entity\Site;
use \NS\SentinelBundle\Form\IBD\Types\CXRResult;
use \NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use \NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use \NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Form\ValidatorGroup\ValidatorGroupResolver;
use \NS\SentinelBundle\Interfaces\SerializedSitesInterface;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('lastName',           null, array('required' => $required, 'label' => 'ibd-form.last-name', 'property_path' => 'lastName'))
            ->add('firstName',          null, array('required' => $required, 'label' => 'ibd-form.first-name', 'property_path' => 'firstName'))
            ->add('parentalName',       null, array('required' => $required, 'label' => 'ibd-form.parental-name'))
            ->add('dobKnown',           'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.date-of-birth-known', 'hidden-child' => 'dob', 'exclude_choices'=> ($isPaho ? [TripleChoice::UNKNOWN]:null)))
            ->add('birthdate',          'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.date-of-birth', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::YES, 'widget' => 'single_text', 'property_path' => 'birthdate'))
            ->add('dobYears',           null, array('required' => $required, 'label' => 'ibd-form.date-of-birth-years', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::NO))
            ->add('dobMonths',          null, array('required' => $required, 'label' => 'ibd-form.date-of-birth-months', 'hidden-parent' => 'dob', 'hidden-value' => TripleChoice::NO))
            ->add('gender',             'NS\SentinelBundle\Form\Types\Gender', array('required' => $required, 'label' => 'ibd-form.gender', 'property_path' => 'gender'))
            ->add('district',           null, array('required' => $required, 'label' => 'ibd-form.district'))
            ->add('state',              null, array('required' => $required, 'label' => 'ibd-form.state'))
            ->add('caseId',             null, array('required' => true, 'label' => 'ibd-form.case-id', 'property_path' => 'case_id'))
            ->add('admDate',            'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.adm-date', 'property_path' => 'adm_date'))
            ->add('admDx',              'NS\SentinelBundle\Form\IBD\Types\Diagnosis', array('required' => $required, 'label' => 'ibd-form.adm-dx', 'hidden-child' => 'admissionDiagnosis'))
            ->add('admDxOther',         null, array('required' => $required, 'label' => 'ibd-form.adm-dx-other', 'hidden-parent' => 'admissionDiagnosis', 'hidden-value' => Diagnosis::OTHER))
            ->add('onsetDate',          'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.onset-date', 'property_path' => 'onset_date'))
            ->add('antibiotics',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.antibiotics'))
            ->add('menSeizures',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-seizures'))
            ->add('menFever',           'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-fever'))
            ->add('menAltConscious',    'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-alt-conscious'))
            ->add('menInabilityFeed',   'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-inability-feed'))
            ->add('menNeckStiff',       'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-stiff-neck'))
            ->add('menRash',            'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-rash'))
            ->add('menFontanelleBulge', 'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-fontanelle-bulge'))
            ->add('menLethargy',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-lethargy'))
            ->add('hibReceived',        'NS\SentinelBundle\Form\Types\VaccinationReceived', array('required' => $required, 'label' => 'ibd-form.hib-received', 'hidden-child' => 'hibReceived'))
            ->add('hibDoses',           'NS\SentinelBundle\Form\Types\FourDoses', array('required' => $required, 'label' => 'ibd-form.hib-doses', 'hidden-parent' => 'hibReceived', 'hidden-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY))))
            ->add('hibMostRecentDose',  'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.hib-most-recent-dose', 'hidden-parent' => 'hibReceived', 'hidden-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY))))
            ->add('pcvReceived',        'NS\SentinelBundle\Form\Types\VaccinationReceived', array('required' => $required, 'label' => 'ibd-form.pcv-received', 'hidden-child' => 'pcvReceived'))
            ->add('pcvDoses',           'NS\SentinelBundle\Form\Types\FourDoses', array('required' => $required, 'label' => 'ibd-form.pcv-doses', 'hidden-parent' => 'pcvReceived', 'hidden-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY))))
            ->add('pcvType',            'NS\SentinelBundle\Form\IBD\Types\PCVType', array('required' => $required, 'label' => 'ibd-form.pcv-type', 'hidden-parent' => 'pcvReceived', 'hidden-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY))))
            ->add('pcvMostRecentDose',  'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.pcv-most-recent-dose', 'hidden-parent' => 'pcvReceived', 'hidden-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY))))
            ->add('meningReceived',     'NS\SentinelBundle\Form\Types\VaccinationReceived', array('required' => $required, 'label' => 'ibd-form.men-received', 'hidden-child' => 'meningReceived'))
            ->add('meningType',         'NS\SentinelBundle\Form\IBD\Types\VaccinationType', array('required' => $required, 'label' => 'ibd-form.men-type', 'hidden-parent' => 'meningReceived', 'hidden-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY))))
            ->add('meningDate',         'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.meningMostRecentDose', 'hidden-parent' => 'meningReceived', 'hidden-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY)), 'property_path' => 'mening_date'))
            ->add('bloodCollected',     'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.blood-collected', 'hidden-child' => 'bloodCollected'))
            ->add('bloodCollectDate',   'NS\AceBundle\Form\DateTimePickerType', array('required' => $required, 'label' => 'ibd-form.blood-collect-date', 'hidden-parent' => 'bloodCollected', 'hidden-value' => TripleChoice::YES))
            ->add('bloodCollectTime',   'Symfony\Component\Form\Extension\Core\Type\TimeType', array('required' => $required, 'label' => 'ibd-form.blood-collect-time', 'hidden-parent' => 'bloodCollected', 'hidden-value' => TripleChoice::YES))
            ->add('csfCollected',       'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.csf-collected', 'hidden-child' => 'csfCollected','exclude_choices'=> ($isPaho ? [TripleChoice::UNKNOWN]:null)))
            ->add('csfCollectDate',     'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.csf-collect-datetime', 'hidden-parent' => 'csfCollected', 'hidden-value' => TripleChoice::YES))
            ->add('csfCollectTime',     'Symfony\Component\Form\Extension\Core\Type\TimeType', array('widget' => 'single_text', 'required' => $required, 'label' => 'ibd-form.csf-collect-datetime', 'hidden-parent' => 'csfCollected', 'hidden-value' => TripleChoice::YES))
            ->add('csfAppearance',      'NS\SentinelBundle\Form\IBD\Types\CSFAppearance', array('required' => $required, 'label' => 'ibd-form.csf-appearance', 'hidden-parent' => 'csfCollected', 'hidden-value' => TripleChoice::YES))
            ->add('otherSpecimenCollected', 'NS\SentinelBundle\Form\IBD\Types\OtherSpecimen', array('required' => $required, 'label' => 'ibd-form.otherSpecimenCollected', 'hidden-child' => 'otherSpecimenCollected'))
            ->add('otherSpecimenOther', null, array('required' => $required, 'label' => 'ibd-form.otherSpecimenOther', 'hidden-parent' => 'otherSpecimenCollected', 'hidden-value'  => OtherSpecimen::OTHER))
            ->add('dischOutcome',       'NS\SentinelBundle\Form\IBD\Types\DischargeOutcome', array('required' => false, 'label' => 'ibd-form.discharge-outcome'))
            ->add('dischDx',            'NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis', array('required' => false, 'label' => 'ibd-form.discharge-diagnosis', 'hidden-child' => 'dischargeDiagnosis'))
            ->add('dischDxOther',       null, array('required' => false, 'label' => 'ibd-form.discharge-diagnosis-other', 'hidden-parent' => 'dischargeDiagnosis', 'hidden-value' => DischargeDiagnosis::OTHER))
            ->add('dischClass',         'NS\SentinelBundle\Form\IBD\Types\DischargeClassification', array('required' => false, 'label' => 'ibd-form.discharge-class','exclude_choices'=> ($isPaho ? [DischargeClassification::UNKNOWN,DischargeClassification::SUSPECT]:null)))
            ->add('comment',            null, array('required' => false, 'label' => 'ibd-form.comment'));
        ;

        $builder->addEventListener(FormEvents::POST_SET_DATA, array($this,'postSetData'));
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
            $form->add('pneuDiffBreathe',       'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.pneu-diff-breathe'))
                ->add('pneuChestIndraw',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.pneu-chest-indraw'))
                ->add('pneuCough',              'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.pneu-cough'))
                ->add('pneuCyanosis',           'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.pneu-cyanosis'))
                ->add('pneuStridor',            'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.pneu-stridor'))
                ->add('pneuRespRate',           null, array('required' => $required, 'label' => 'ibd-form.pneu-resp-rate', 'property_path' => 'pneu_resp_rate'))
                ->add('pneuVomit',              'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.pneu-vomit'))
                ->add('pneuHypothermia',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.pneu-hypothermia'))
                ->add('pneuMalnutrition',       'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.pneu-malnutrition'))
                ->add('cxrDone',                'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.cxr-done', 'hidden-child' => 'cxrDone'))
                ->add('cxrResult',              'NS\SentinelBundle\Form\IBD\Types\CXRResult', array('required' => $required, 'label' => 'ibd-form.cxr-result', 'hidden-parent' => 'cxrDone', 'hidden-child' => 'cxrResult', 'hidden-value' => TripleChoice::YES))
                ->add('cxrAdditionalResult',    'NS\SentinelBundle\Form\IBD\Types\CXRAdditionalResult', array('required' => $required, 'label' => 'ibd-form.cxr-additional-result', 'hidden-parent' => 'cxrResult', 'hidden-value' => CXRResult::CONSISTENT))
            ;

            if ($this->authChecker->isGranted('ROLE_AMR')) {
                $form->add('pneuOxygenSaturation', null, array('required' => $required, 'label' => 'ibd-form.pneu-oxygen-level', 'property_path' => 'pneu_oxygen_saturation'));
            }
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\IBD',
            'validation_groups' => $this->validatorResolver,
        ));
    }
}
