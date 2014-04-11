<?php

namespace NS\SentinelBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
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

class MeningitisType extends AbstractType
{
    private $em;
    private $siteSerializer;

    public function __construct(SerializedSites $siteSerializer, ObjectManager $em)
    {
        $this->siteSerializer = $siteSerializer;
        $this->em             = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dob',                    'acedatepicker',    array('required'=>false,'label'=>'meningitis-form.date-of-birth','widget'=>'single_text'))
            ->add('ageInMonths',            null,               array('required'=>false,'label'=>'meningitis-form.age-in-months'))
            ->add('gender',                 'Gender',           array('required'=>false,'label'=>'meningitis-form.gender'))
            ->add('district',               null,               array('required'=>false,'label'=>'meningitis-form.district'))
            ->add('caseId',                 null,               array('required'=>false,'label'=>'meningitis-form.case-id'))
            ->add('admDate',                'acedatepicker',    array('required'=>false,'label'=>'meningitis-form.adm-date'))
            ->add('admDx',                  'Diagnosis',        array('required'=>false,'label'=>'meningitis-form.adm-dx',       'attr' => array('data-context-field'=>'admissionDiagnosis')))
            ->add('admDxOther',             null,               array('required'=>false,'label'=>'meningitis-form.adm-dx-other', 'attr' => array('data-context-field'=>'admissionDiagnosis', 'data-context-value'=>Diagnosis::OTHER)))
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

            ->add('hibReceived',            'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.hib-received',    'attr' => array('data-context-field'=>'hibReceived')))
            ->add('hibDoses',               'Doses',            array('required'=>false,'label'=>'meningitis-form.hib-doses',       'attr' => array('data-context-field'=>'hibReceived', 'data-context-value'=>TripleChoice::YES)))

            ->add('pcvReceived',            'TripleChoice',     array('required'=>false,'label'=>'meningitis-form.pcv-received',    'attr' => array('data-context-field'=>'pcvReceived')))
            ->add('pcvDoses',               'Doses',            array('required'=>false,'label'=>'meningitis-form.pcv-doses',       'attr' => array('data-context-field'=>'pcvReceived', 'data-context-value'=>TripleChoice::YES)))
            ->add('meningReceived',         'MeningitisVaccinationReceived',array('required'=>false,'label'=>'meningitis-form.men-received',        'attr' => array('data-context-field'=>'meningReceived')))
            ->add('meningType',             'MeningitisVaccinationType',    array('required'=>false,'label'=>'meningitis-form.men-type',            'attr' => array('data-context-field'=>'meningReceived', 'data-context-value'=>json_encode(array(MeningitisVaccinationReceived::YES_CARD,MeningitisVaccinationReceived::YES_HISTORY)))))
            ->add('meningMostRecentDose',   'acedatepicker',    array('required'=>false,'label'=>'meningitis-form.meningMostRecentDose',            'attr' => array('data-context-field'=>'meningReceived', 'data-context-value'=>json_encode(array(MeningitisVaccinationReceived::YES_CARD,MeningitisVaccinationReceived::YES_HISTORY)))))

            ->add('csfCollected',       'switch',               array('required'=>false,'label'=>'meningitis-form.csf-collected','switchtype'=>2,   'attr' => array('data-context-field'=>'csfCollected')))
            ->add('csfId',              null,                   array('required'=>false,'label'=>'meningitis-form.csf-id',                          'attr' => array('data-context-field'=>'csfCollected','data-context-value'=>true)))
            ->add('csfCollectDateTime', 'acedatetime',          array('required'=>false,'label'=>'meningitis-form.csf-collect-datetime',            'attr' => array('data-context-field'=>'csfCollected','data-context-value'=>true)))
            ->add('csfAppearance',      'CSFAppearance',        array('required'=>false,'label'=>'meningitis-form.csf-appearance',                  'attr' => array('data-context-field'=>'csfCollected','data-context-value'=>true)))
            ->add('bloodCollected',     'switch',               array('required'=>false,'label'=>'meningitis-form.blood-collected','switchtype'=>2))
            ->add('dischOutcome',       'DischargeOutcome',     array('required'=>false,'label'=>'meningitis-form.discharge-outcome'))
            ->add('dischDx',            'Diagnosis',            array('required'=>false,'label'=>'meningitis-form.discharge-diagnosis',             'attr' => array('data-context-field'=>'dischargeDiagnosis')))
            ->add('dischDxOther',       null,                   array('required'=>false,'label'=>'meningitis-form.discharge-diagnosis-other',       'attr' => array('data-context-field'=>'dischargeDiagnosis', 'data-context-field'=>Diagnosis::OTHER)))
            ->add('dischClass',         'DischargeClassification',array('required'=>false,'label'=>'meningitis-form.discharge-class'))
            ->add('comment',            null,                   array('required'=>false,'label'=>'meningitis-form.comment'))
        ;

        $factory        = $builder->getFormFactory();
        $siteSerializer = $this->siteSerializer;
        $em             = $this->em;

        $builder->addEventListener(
                        FormEvents::PRE_SET_DATA,
                        function(FormEvent $event) use($factory,$siteSerializer,$em)
                        {
                            $form = $event->getForm();

                            if($siteSerializer->hasMultipleSites())
                            {
                                $form->add($factory->createNamed('site','entity',null,array('required'        => true,
                                                                                            'empty_value'     => 'Please Select...',
                                                                                            'label'           => 'rotavirus-form.site',
                                                                                            'query_builder'   => $em->getRepository('NS\SentinelBundle\Entity\Site')->getChainQueryBuilder(),
                                                                                            'class'           => 'NS\SentinelBundle\Entity\Site',
                                                                                            'auto_initialize' => false))
                                          );
                            }
                        }
            );

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
                        $form->add('pneuDiffBreathe','TripleChoice',array('required'=>false,'label'=>'meningitis-form.pneu-diff-breathe'))
                             ->add('pneuChestIndraw','TripleChoice',array('required'=>false,'label'=>'meningitis-form.pneu-chest-indraw'))
                             ->add('pneuCough','TripleChoice',array('required'=>false,'label'=>'meningitis-form.pneu-cough'))
                             ->add('pneuCyanosis','TripleChoice',array('required'=>false,'label'=>'meningitis-form.pneu-cyanosis'))
                             ->add('pneuStridor','TripleChoice',array('required'=>false,'label'=>'meningitis-form.pneu-stridor'))
                             ->add('pneuRespRate',null,array('required'=>false,'label'=>'meningitis-form.pneu-resp-rate'))
                             ->add('pneuVomit','TripleChoice',array('required'=>false,'label'=>'meningitis-form.pneu-vomit'))
                             ->add('pneuHypothermia','TripleChoice',array('required'=>false,'label'=>'meningitis-form.pneu-hypothermia'))
                             ->add('pneuMalnutrition','TripleChoice',array('required'=>false,'label'=>'meningitis-form.pneu-malnutrition'));
                    }

                    if($country instanceof Country )
                    {
                        if($country->hasReferenceLab())
                            $form->add('sentToReferenceLab','switch',array('required'=>false));

                        if($country->hasNationalLab())
                            $form->add('sentToNationalLab','switch',array('required'=>false));
                    }
                });

        $builder->addEventListener(
                        FormEvents::SUBMIT,
                        function(FormEvent $event) use ($siteSerializer)
                        {
                            if($siteSerializer->hasMultipleSites()) // they'll be choosing so exit
                                return;

                            $data = $event->getData();
                            if(!$data || $data->hasId()) // no editing of sites
                                return;

                            // current gets us the one site we are able to see since we test for count > 1 above
                            $site = $siteSerializer->getSite(true);
                            $data->setSite($site);

                            $event->setData($data);
                        }
                );
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
        return 'meningitis';
    }
}
