<?php

namespace NS\SentinelBundle\Form\IBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Form\Types\Diagnosis;
use NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Services\SerializedSites;

class CaseType extends AbstractType
{
    private $siteSerializer;

    public function __construct(SerializedSites $siteSerializer)
    {
        $this->siteSerializer = $siteSerializer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName',               null,               array('required'=>false,'label'=>'meningitis-form.last-name'))
            ->add('firstName',              null,               array('required'=>false,'label'=>'meningitis-form.first-name'))
            ->add('dob',                    'acedatepicker',    array('required'=>false,'label'=>'meningitis-form.date-of-birth','widget'=>'single_text'))
            ->add('age',                    null,               array('required'=>false,'label'=>'meningitis-form.age-in-months'))
            ->add('gender',                 'Gender',           array('required'=>false,'label'=>'meningitis-form.gender'))
            ->add('district',               null,               array('required'=>false,'label'=>'meningitis-form.district'))
            ->add('caseId',                 null,               array('required'=>false,'label'=>'meningitis-form.case-id'))
            ->add('admDate',                'acedatepicker',    array('required'=>false,'label'=>'meningitis-form.adm-date'))
            ->add('admDx',                  'Diagnosis',        array('required'=>false,'label'=>'meningitis-form.adm-dx',       'attr' => array('data-context-child'=>'admissionDiagnosis')))
            ->add('admDxOther',             null,               array('required'=>false,'label'=>'meningitis-form.adm-dx-other', 'attr' => array('data-context-parent'=>'admissionDiagnosis', 'data-context-value'=>Diagnosis::OTHER)))
            ->add('onsetDate',              'acedatepicker',    array('required'=>false,'label'=>'meningitis-form.onset-date'))
            ->add('antibiotics',            'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.antibiotics'))

            ->add('menSeizures',            'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.men-seizures'))
            ->add('menFever',               'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.men-fever'))
            ->add('menAltConscious',        'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.men-alt-conscious'))
            ->add('menInabilityFeed',       'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.men-inability-feed'))
            ->add('menNeckStiff',           'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.men-stiff-neck'))
            ->add('menRash',                'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.men-rash'))
            ->add('menFontanelleBulge',     'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.men-fontanelle-bulge'))
            ->add('menLethargy',            'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.men-lethargy'))

            ->add('hibReceived',            'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.hib-received',              'attr' => array('data-context-child'=>'hibReceived')))
            ->add('hibDoses',               'Doses',            array('required'=>false,'label'=>'meningitis-form.hib-doses',                 'attr' => array('data-context-parent'=>'hibReceived', 'data-context-value'=>TripleChoice::YES)))

            ->add('pcvReceived',            'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.pcv-received',              'attr' => array('data-context-child'=>'pcvReceived')))
            ->add('pcvDoses',               'Doses',            array('required'=>false,'label'=>'meningitis-form.pcv-doses',                 'attr' => array('data-context-parent'=>'pcvReceived', 'data-context-value'=>TripleChoice::YES)))
            ->add('meningReceived',         'MeningitisVaccinationReceived',array('required'=>false,'label'=>'meningitis-form.men-received',  'attr' => array('data-context-child'=>'meningReceived')))
            ->add('meningType',             'MeningitisVaccinationType',    array('required'=>false,'label'=>'meningitis-form.men-type',      'attr' => array('data-context-parent'=>'meningReceived', 'data-context-value'=>json_encode(array(MeningitisVaccinationReceived::YES_CARD,MeningitisVaccinationReceived::YES_HISTORY)))))
            ->add('meningMostRecentDose',   'acedatepicker',    array('required'=>false,'label'=>'meningitis-form.meningMostRecentDose',      'attr' => array('data-context-parent'=>'meningReceived', 'data-context-value'=>json_encode(array(MeningitisVaccinationReceived::YES_CARD,MeningitisVaccinationReceived::YES_HISTORY)))))

            ->add('csfCollected',           'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.csf-collected',             'attr' => array('data-context-child'=>'csfCollected','data-context-value'=>TripleChoice::YES)))
            ->add('csfId',                  null,               array('required'=>false,'label'=>'meningitis-form.csf-id',                    'attr' => array('data-context-parent'=>'csfCollected','data-context-value'=>true)))
            ->add('csfCollectDateTime',     'acedatetime',      array('required'=>false,'label'=>'meningitis-form.csf-collect-datetime',      'attr' => array('data-context-parent'=>'csfCollected','data-context-value'=>true)))
            ->add('csfAppearance',          'CSFAppearance',    array('required'=>false,'label'=>'meningitis-form.csf-appearance',            'attr' => array('data-context-parent'=>'csfCollected','data-context-value'=>true)))

            ->add('dischOutcome',           'DischargeOutcome', array('required'=>false,'label'=>'meningitis-form.discharge-outcome'))
            ->add('dischDx',                'Diagnosis',        array('required'=>false,'label'=>'meningitis-form.discharge-diagnosis',       'attr' => array('data-context-child'=>'dischargeDiagnosis')))
            ->add('dischDxOther',           null,               array('required'=>false,'label'=>'meningitis-form.discharge-diagnosis-other', 'attr' => array('data-context-parent'=>'dischargeDiagnosis', 'data-context-parent'=>Diagnosis::OTHER)))
            ->add('dischClass',       'DischargeClassification',array('required'=>false,'label'=>'meningitis-form.discharge-class'))
            ->add('comment',                null,               array('required'=>false,'label'=>'meningitis-form.comment'))

            ->add('cxrDone',            'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.cxr-done',     'attr' => array('data-context-child'=>'cxrDone')))
            ->add('cxrResult',          'CXRResult',            array('required'=>false, 'label'=>'meningitis-form.cxr-result',   'attr' => array('data-context-parent'=>'cxrDone','data-context-value'=> TripleChoice::YES)))
        ;

        $siteSerializer = $this->siteSerializer;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use($siteSerializer)
                {
                    $data    = $event->getData();
                    $form    = $event->getForm();
                    $country = null;

                    if($data && $data->getCountry())
                        $country = $data->getCountry();
                    else if(!$siteSerializer->hasMultipleSites())
                    {
                        $site    = $siteSerializer->getSite();
                        $country = ($site instanceof \NS\SentinelBundle\Entity\Site) ? $site->getCountry():null;
                    }

                    if(!$country || ($country instanceof Country && $country->getTracksPneumonia()))
                    {
                        $form->add('pneuDiffBreathe',   'TripleChoice', array('required'=>false,'label'=>'meningitis-form.pneu-diff-breathe'))
                             ->add('pneuChestIndraw',   'TripleChoice', array('required'=>false,'label'=>'meningitis-form.pneu-chest-indraw'))
                             ->add('pneuCough',         'TripleChoice', array('required'=>false,'label'=>'meningitis-form.pneu-cough'))
                             ->add('pneuCyanosis',      'TripleChoice', array('required'=>false,'label'=>'meningitis-form.pneu-cyanosis'))
                             ->add('pneuStridor',       'TripleChoice', array('required'=>false,'label'=>'meningitis-form.pneu-stridor'))
                             ->add('pneuRespRate',      null,           array('required'=>false,'label'=>'meningitis-form.pneu-resp-rate'))
                             ->add('pneuVomit',         'TripleChoice', array('required'=>false,'label'=>'meningitis-form.pneu-vomit'))
                             ->add('pneuHypothermia',   'TripleChoice', array('required'=>false,'label'=>'meningitis-form.pneu-hypothermia'))
                             ->add('pneuMalnutrition',  'TripleChoice', array('required'=>false,'label'=>'meningitis-form.pneu-malnutrition'))
                             ->add('bloodCollected',    'TripleChoice', array('required'=>false,'label'=>'meningitis-form.blood-collected'))
                             ->add('bloodId',           null,           array('required'=>false));
                    }
                });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\Meningitis'
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
