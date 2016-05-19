<?php

namespace NS\SentinelBundle\Form\IBD;

use \NS\SentinelBundle\Entity\Country;
use \NS\SentinelBundle\Entity\Site;
use \NS\SentinelBundle\Form\IBD\Types\CXRResult;
use \NS\SentinelBundle\Form\IBD\Types\Diagnosis;
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

class CaseType extends AbstractType
{
    /**
     * @var SerializedSitesInterface
     */
    private $siteSerializer;

    /**
     * @var ValidatorGroupResolver
     */
    private $validatorResolver;

    /**
     *
     * @param SerializedSitesInterface $siteSerializer
     */
    public function __construct(SerializedSitesInterface $siteSerializer, ValidatorGroupResolver $resolver)
    {
        $this->siteSerializer = $siteSerializer;
        $this->validatorResolver = $resolver;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $required = (isset($options['method']) && $options['method'] == 'PUT');

        $builder
            ->add('lastName',           null, array('required' => $required, 'label' => 'ibd-form.last-name'))
            ->add('firstName',          null, array('required' => $required, 'label' => 'ibd-form.first-name'))
            ->add('parentalName',       null, array('required' => $required, 'label' => 'ibd-form.parental-name'))
            ->add('dobKnown',           'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.date-of-birth-known', 'attr' => array('data-context-child' => 'dob')))
            ->add('birthdate',          'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.date-of-birth', 'attr' => array('data-context-parent' => 'dob', 'data-context-value' => TripleChoice::YES), 'widget' => 'single_text'))
            ->add('dobYears',           null, array('required' => $required, 'label' => 'ibd-form.date-of-birth-years', 'attr' => array('data-context-parent' => 'dob', 'data-context-value' => TripleChoice::NO)))
            ->add('dobMonths',          null, array('required' => $required, 'label' => 'ibd-form.date-of-birth-months', 'attr' => array('data-context-parent' => 'dob', 'data-context-value' => TripleChoice::NO)))
            ->add('gender',             'NS\SentinelBundle\Form\Types\Gender', array('required' => $required, 'label' => 'ibd-form.gender'))
            ->add('district',           null, array('required' => $required, 'label' => 'ibd-form.district'))
            ->add('state',              null, array('required' => $required, 'label' => 'ibd-form.state'))
            ->add('caseId',             null, array('required' => true, 'label' => 'ibd-form.case-id'))
            ->add('admDate',            'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.adm-date'))
            ->add('admDx',              'NS\SentinelBundle\Form\IBD\Types\Diagnosis', array('required' => $required, 'label' => 'ibd-form.adm-dx', 'attr' => array('data-context-child' => 'admissionDiagnosis')))
            ->add('admDxOther',         null, array('required' => $required, 'label' => 'ibd-form.adm-dx-other', 'attr' => array('data-context-parent' => 'admissionDiagnosis', 'data-context-value' => Diagnosis::OTHER)))
            ->add('onsetDate',          'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.onset-date'))
            ->add('antibiotics',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.antibiotics'))
            ->add('menSeizures',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-seizures'))
            ->add('menFever',           'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-fever'))
            ->add('menAltConscious',    'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-alt-conscious'))
            ->add('menInabilityFeed',   'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-inability-feed'))
            ->add('menNeckStiff',       'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-stiff-neck'))
            ->add('menRash',            'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-rash'))
            ->add('menFontanelleBulge', 'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-fontanelle-bulge'))
            ->add('menLethargy',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-lethargy'))
            ->add('hibReceived',        'NS\SentinelBundle\Form\Types\VaccinationReceived', array('required' => $required, 'label' => 'ibd-form.hib-received', 'attr' => array('data-context-child' => 'hibReceived')))
            ->add('hibDoses',           'NS\SentinelBundle\Form\Types\FourDoses', array('required' => $required, 'label' => 'ibd-form.hib-doses', 'attr' => array('data-context-parent' => 'hibReceived', 'data-context-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY)))))
            ->add('hibMostRecentDose',  'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.hib-most-recent-dose', 'attr' => array('data-context-parent' => 'hibReceived', 'data-context-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY)))))
            ->add('pcvReceived',        'NS\SentinelBundle\Form\Types\VaccinationReceived', array('required' => $required, 'label' => 'ibd-form.pcv-received', 'attr' => array('data-context-child' => 'pcvReceived')))
            ->add('pcvDoses',           'NS\SentinelBundle\Form\Types\FourDoses', array('required' => $required, 'label' => 'ibd-form.pcv-doses', 'attr' => array('data-context-parent' => 'pcvReceived', 'data-context-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY)))))
            ->add('pcvType',            'NS\SentinelBundle\Form\IBD\Types\PCVType', array('required' => $required, 'label' => 'ibd-form.pcv-type', 'attr' => array('data-context-parent' => 'pcvReceived', 'data-context-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY)))))
            ->add('pcvMostRecentDose',  'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.pcv-most-recent-dose', 'attr' => array('data-context-parent' => 'pcvReceived', 'data-context-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY)))))
            ->add('meningReceived',     'NS\SentinelBundle\Form\Types\VaccinationReceived', array('required' => $required, 'label' => 'ibd-form.men-received', 'attr' => array('data-context-child' => 'meningReceived')))
            ->add('meningType',         'NS\SentinelBundle\Form\IBD\Types\VaccinationType', array('required' => $required, 'label' => 'ibd-form.men-type', 'attr' => array('data-context-parent' => 'meningReceived', 'data-context-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY)))))
            ->add('meningDate',         'NS\AceBundle\Form\DatePickerType', array('required' => $required, 'label' => 'ibd-form.meningMostRecentDose', 'attr' => array('data-context-parent' => 'meningReceived', 'data-context-value' => json_encode(array(VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY)))))
            ->add('bloodCollected',     'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.blood-collected', 'attr' => array('data-context-child' => 'bloodCollected')))
            ->add('bloodCollectDate',   'NS\AceBundle\Form\DateTimePickerType', array('required' => $required, 'label' => 'ibd-form.blood-collect-date', 'attr' => array('data-context-parent' => 'bloodCollected', 'data-context-value' => TripleChoice::YES)))
            ->add('csfCollected',       'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.csf-collected', 'attr' => array('data-context-child' => 'csfCollected')))
            ->add('csfCollectDate',     'date', array('required' => $required, 'label' => 'ibd-form.csf-collect-datetime', 'attr' => array('data-context-parent' => 'csfCollected', 'data-context-value' => TripleChoice::YES)))
            ->add('csfCollectTime',     'time', array('widget' => 'single_text', 'required' => $required, 'label' => 'ibd-form.csf-collect-datetime', 'attr' => array('data-context-parent' => 'csfCollected', 'data-context-value' => TripleChoice::YES)))
            ->add('csfAppearance',      'NS\SentinelBundle\Form\IBD\Types\CSFAppearance', array('required' => $required, 'label' => 'ibd-form.csf-appearance', 'attr' => array('data-context-parent' => 'csfCollected', 'data-context-value' => TripleChoice::YES)))
            ->add('otherSpecimenCollected', 'NS\SentinelBundle\Form\IBD\Types\OtherSpecimen', array('required' => $required, 'label' => 'ibd-form.otherSpecimenCollected', 'attr' => array('data-context-child' => 'otherSpecimenCollected')))
            ->add('otherSpecimenOther', null, array('required' => $required, 'label' => 'ibd-form.otherSpecimenOther', 'attr' => array('data-context-parent' => 'otherSpecimenCollected', 'data-context-value'  => OtherSpecimen::OTHER)))
            ->add('dischOutcome',       'NS\SentinelBundle\Form\IBD\Types\DischargeOutcome', array('required' => false, 'label' => 'ibd-form.discharge-outcome'))
            ->add('dischDx',            'NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis', array('required' => false, 'label' => 'ibd-form.discharge-diagnosis', 'attr' => array('data-context-child' => 'dischargeDiagnosis')))
            ->add('dischDxOther',       null, array('required' => false, 'label' => 'ibd-form.discharge-diagnosis-other', 'attr' => array('data-context-parent' => 'dischargeDiagnosis', 'data-context-value' => DischargeDiagnosis::OTHER)))
            ->add('dischClass',         'NS\SentinelBundle\Form\IBD\Types\DischargeClassification', array('required' => false, 'label' => 'ibd-form.discharge-class'))
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
                ->add('pneuRespRate',           null, array('required' => $required, 'label' => 'ibd-form.pneu-resp-rate'))
                ->add('pneuVomit',              'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.pneu-vomit'))
                ->add('pneuHypothermia',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.pneu-hypothermia'))
                ->add('pneuMalnutrition',       'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.pneu-malnutrition'))
                ->add('cxrDone',                'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => $required, 'label' => 'ibd-form.cxr-done', 'attr' => array('data-context-child' => 'cxrDone')))
                ->add('cxrResult',              'NS\SentinelBundle\Form\IBD\Types\CXRResult', array('required' => $required, 'label' => 'ibd-form.cxr-result', 'attr' => array('data-context-parent' => 'cxrDone', 'data-context-child' => 'cxrResult', 'data-context-value' => TripleChoice::YES)))
                ->add('cxrAdditionalResult',    'NS\SentinelBundle\Form\IBD\Types\CXRAdditionalResult', array('required' => $required, 'label' => 'ibd-form.cxr-additional-result', 'attr' => array('data-context-parent' => 'cxrResult', 'data-context-value' => CXRResult::CONSISTENT)))
            ;
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
