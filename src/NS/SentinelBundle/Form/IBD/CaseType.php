<?php

namespace NS\SentinelBundle\Form\IBD;

use \NS\SentinelBundle\Entity\Country;
use \NS\SentinelBundle\Entity\Site;
use \NS\SentinelBundle\Form\Types\CXRResult;
use \NS\SentinelBundle\Form\Types\Diagnosis;
use \NS\SentinelBundle\Form\Types\OtherSpecimen;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\VaccinationReceived;
use \NS\SentinelBundle\Interfaces\SerializedSitesInterface;
use \NS\SentinelBundle\Services\SerializedSites;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CaseType extends AbstractType
{
    private $siteSerializer;

    /**
     *
     * @param SerializedSites $siteSerializer
     */
    public function __construct(SerializedSitesInterface $siteSerializer)
    {
        $this->siteSerializer = $siteSerializer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $required = (isset($options['method']) && $options['method'] == 'PUT');

        $builder
            ->add('lastName', null, array('required' => $required, 'label' => 'ibd-form.last-name'))
            ->add('firstName', null, array('required' => $required, 'label' => 'ibd-form.first-name'))
            ->add('parentalName', null, array('required' => $required, 'label' => 'ibd-form.parental-name'))
            ->add('dobKnown', 'TripleChoice', array('required' => $required, 'label' => 'ibd-form.date-of-birth-known',
                'attr' => array('data-context-child' => 'dob')))
            ->add('dob', 'acedatepicker', array('required' => $required, 'label' => 'ibd-form.date-of-birth',
                'attr' => array('data-context-parent' => 'dob', 'data-context-value' => TripleChoice::YES),
                'widget' => 'single_text'))
            ->add('dobYears', null, array('required' => $required, 'label' => 'ibd-form.date-of-birth-years',
                'attr' => array('data-context-parent' => 'dob', 'data-context-value' => TripleChoice::NO)))
            ->add('dobMonths', null, array('required' => $required, 'label' => 'ibd-form.date-of-birth-months',
                'attr' => array('data-context-parent' => 'dob', 'data-context-value' => TripleChoice::NO)))
            ->add('gender', 'Gender', array('required' => $required, 'label' => 'ibd-form.gender'))
            ->add('district', null, array('required' => $required, 'label' => 'ibd-form.district'))
            ->add('caseId', null, array('required' => true, 'label' => 'ibd-form.case-id'))
            ->add('admDate', 'acedatepicker', array('required' => $required, 'label' => 'ibd-form.adm-date'))
            ->add('admDx', 'Diagnosis', array('required' => $required, 'label' => 'ibd-form.adm-dx',
                'attr' => array('data-context-child' => 'admissionDiagnosis')))
            ->add('admDxOther', null, array('required' => $required, 'label' => 'ibd-form.adm-dx-other',
                'attr' => array('data-context-parent' => 'admissionDiagnosis', 'data-context-value' => Diagnosis::OTHER)))
            ->add('onsetDate', 'acedatepicker', array('required' => $required, 'label' => 'ibd-form.onset-date'))
            ->add('antibiotics', 'TripleChoice', array('required' => $required, 'label' => 'ibd-form.antibiotics'))
            ->add('menSeizures', 'TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-seizures'))
            ->add('menFever', 'TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-fever'))
            ->add('menAltConscious', 'TripleChoice', array('required' => $required,
                'label' => 'ibd-form.men-alt-conscious'))
            ->add('menInabilityFeed', 'TripleChoice', array('required' => $required,
                'label' => 'ibd-form.men-inability-feed'))
            ->add('menNeckStiff', 'TripleChoice', array('required' => $required,
                'label' => 'ibd-form.men-stiff-neck'))
            ->add('menRash', 'TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-rash'))
            ->add('menFontanelleBulge', 'TripleChoice', array('required' => $required,
                'label' => 'ibd-form.men-fontanelle-bulge'))
            ->add('menLethargy', 'TripleChoice', array('required' => $required, 'label' => 'ibd-form.men-lethargy'))
            ->add('hibReceived', 'VaccinationReceived', array('required' => $required,
                'label' => 'ibd-form.hib-received', 'attr' => array('data-context-child' => 'hibReceived')))
            ->add('hibDoses', 'FourDoses', array('required' => $required, 'label' => 'ibd-form.hib-doses',
                'attr' => array('data-context-parent' => 'hibReceived', 'data-context-value' => json_encode(array(
                        VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY)))))
            ->add('hibMostRecentDose', 'acedatepicker', array('required' => $required,
                'label' => 'ibd-form.hib-most-recent-dose', 'attr' => array('data-context-parent' => 'hibReceived',
                    'data-context-value' => json_encode(array(VaccinationReceived::YES_CARD,
                        VaccinationReceived::YES_HISTORY)))))
            ->add('pcvReceived', 'VaccinationReceived', array('required' => $required,
                'label' => 'ibd-form.pcv-received', 'attr' => array('data-context-child' => 'pcvReceived')))
            ->add('pcvDoses', 'FourDoses', array('required' => $required, 'label' => 'ibd-form.pcv-doses',
                'attr' => array('data-context-parent' => 'pcvReceived', 'data-context-value' => json_encode(array(
                        VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY)))))
            ->add('pcvType', 'PCVType', array('required' => $required, 'label' => 'ibd-form.pcv-type',
                'attr' => array('data-context-parent' => 'pcvReceived', 'data-context-value' => json_encode(array(
                        VaccinationReceived::YES_CARD, VaccinationReceived::YES_HISTORY)))))
            ->add('pcvMostRecentDose', 'acedatepicker', array('required' => $required,
                'label' => 'ibd-form.pcv-most-recent-dose', 'attr' => array('data-context-parent' => 'pcvReceived',
                    'data-context-value' => json_encode(array(VaccinationReceived::YES_CARD,
                        VaccinationReceived::YES_HISTORY)))))
            ->add('meningReceived', 'VaccinationReceived', array('required' => $required,
                'label' => 'ibd-form.men-received', 'attr' => array('data-context-child' => 'meningReceived')))
            ->add('meningType', 'MeningitisVaccinationType', array('required' => $required,
                'label' => 'ibd-form.men-type', 'attr' => array('data-context-parent' => 'meningReceived',
                    'data-context-value' => json_encode(array(VaccinationReceived::YES_CARD,
                        VaccinationReceived::YES_HISTORY)))))
            ->add('meningMostRecentDose', 'acedatepicker', array('required' => $required,
                'label' => 'ibd-form.meningMostRecentDose', 'attr' => array('data-context-parent' => 'meningReceived',
                    'data-context-value' => json_encode(array(VaccinationReceived::YES_CARD,
                        VaccinationReceived::YES_HISTORY)))))
            ->add('bloodCollected', 'TripleChoice', array('required' => $required,
                'label' => 'ibd-form.blood-collected'))
            ->add('csfCollected', 'TripleChoice', array('required' => $required,
                'label' => 'ibd-form.csf-collected', 'attr' => array('data-context-child' => 'csfCollected')))
            ->add('csfCollectDateTime', 'acedatetime', array('required' => $required,
                'label' => 'ibd-form.csf-collect-datetime', 'attr' => array('data-context-parent' => 'csfCollected',
                    'data-context-value' => TripleChoice::YES)))
            ->add('csfAppearance', 'CSFAppearance', array('required' => $required,
                'label' => 'ibd-form.csf-appearance', 'attr' => array('data-context-parent' => 'csfCollected',
                    'data-context-value' => TripleChoice::YES)))
            ->add('otherSpecimenCollected', 'OtherSpecimen', array('required' => $required,
                'label' => 'ibd-form.otherSpecimenCollected', 'attr' => array('data-context-child' => 'otherSpecimenCollected')))
            ->add('otherSpecimenOther', null, array('required' => $required, 'label' => 'ibd-form.otherSpecimenOther',
                'attr' => array('data-context-parent' => 'otherSpecimenCollected',
                    'data-context-value'  => OtherSpecimen::OTHER)))
        ;

        $siteSerializer = $this->siteSerializer;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use($siteSerializer,$required)
                {
                    $data    = $event->getData();
                    $form    = $event->getForm();
                    $country = null;

                    if($data && $data->getCountry())
                        $country = $data->getCountry();
                    else if(!$siteSerializer->hasMultipleSites())
                    {
                        $site    = $siteSerializer->getSite();
                        $country = ($site instanceof Site) ? $site->getCountry() : null;
                    }

            if(!$country || ($country instanceof Country && $country->getTracksPneumonia()))
                    {
                        $form->add('pneuDiffBreathe', 'TripleChoice', array('required' => $required,
                        'label' => 'ibd-form.pneu-diff-breathe'))
                    ->add('pneuChestIndraw', 'TripleChoice', array('required' => $required,
                        'label' => 'ibd-form.pneu-chest-indraw'))
                    ->add('pneuCough', 'TripleChoice', array('required' => $required,
                        'label' => 'ibd-form.pneu-cough'))
                    ->add('pneuCyanosis', 'TripleChoice', array('required' => $required,
                        'label' => 'ibd-form.pneu-cyanosis'))
                    ->add('pneuStridor', 'TripleChoice', array('required' => $required,
                        'label' => 'ibd-form.pneu-stridor'))
                    ->add('pneuRespRate', null, array('required' => $required, 'label' => 'ibd-form.pneu-resp-rate'))
                    ->add('pneuVomit', 'TripleChoice', array('required' => $required,
                        'label' => 'ibd-form.pneu-vomit'))
                    ->add('pneuHypothermia', 'TripleChoice', array('required' => $required,
                        'label' => 'ibd-form.pneu-hypothermia'))
                    ->add('pneuMalnutrition', 'TripleChoice', array('required' => $required,
                        'label' => 'ibd-form.pneu-malnutrition'))
                    ->add('cxrDone', 'TripleChoice', array('required' => $required,
                        'label' => 'ibd-form.cxr-done', 'attr' => array('data-context-child' => 'cxrDone')))
                    ->add('cxrResult', 'CXRResult', array('required' => $required,
                        'label' => 'ibd-form.cxr-result', 'attr' => array('data-context-parent' => 'cxrDone',
                            'data-context-child' => 'cxrResult', 'data-context-value' => TripleChoice::YES)))
                    ->add('cxrAdditionalResult', 'CXRAdditionalResult', array('required' => $required,
                        'label' => 'ibd-form.cxr-additional-result', 'attr' => array(
                            'data-context-parent' => 'cxrResult', 'data-context-value' => CXRResult::CONSISTENT)))
                ;
                    }
                });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\IBD'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ibd';
    }
}
