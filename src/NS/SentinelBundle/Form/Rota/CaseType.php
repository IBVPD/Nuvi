<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use NS\SentinelBundle\Services\SerializedSites;
use Doctrine\Common\Persistence\ObjectManager;

class CaseType extends AbstractType
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
            ->add('caseId',                     null,               array('required'=>false, 'label'=>'rotavirus-form.caseId'))
            ->add('gender',                     'Gender',           array('required'=>false, 'label'=>'rotavirus-form.gender'))
            ->add('dob',                        'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.dob'))
            ->add('age',                        null,               array('required'=>false, 'label'=>'rotavirus-form.age-in-months'))
            ->add('district',                   null,               array('required'=>false, 'label'=>'rotavirus-form.district'))
            ->add('admissionDate',              'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.admissionDate'))
            ->add('symptomDiarrhea',            'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrhea'))
            ->add('symptomDiarrheaOnset',       'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaOnset'))
            ->add('symptomDiarrheaEpisodes',    null,               array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaEpisodes'))
            ->add('symptomDiarrheaDuration',    null,               array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaDuration'))
            ->add('symptomDiarrheaVomit',       'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaVomit'))
            ->add('symptomVomitEpisodes',       null,               array('required'=>false, 'label'=>'rotavirus-form.symptomVomitEpisodes'))
            ->add('symptomVomitDuration',       null,               array('required'=>false, 'label'=>'rotavirus-form.symptomVomitDuration'))
            ->add('symptomDehydration',         'Dehydration',      array('required'=>false, 'label'=>'rotavirus-form.symptomDehydration'))
            ->add('rehydration',                'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.rehydration'))
            ->add('rehydrationType',            'Rehydration',      array('required'=>false, 'label'=>'rotavirus-form.rehydrationType'))
            ->add('rehydrationOther',           null,               array('required'=>false, 'label'=>'rotavirus-form.rehydrationOther'))
            ->add('vaccinationReceived',        'RotavirusVaccinationReceived', array('required'=>false, 'label'=>'rotavirus-form.vaccinationReceived'))
            ->add('vaccinationType',            'RotavirusVaccinationType',     array('required'=>false, 'label'=>'rotavirus-form.vaccinationType'))
            ->add('doses',                      'Doses',            array('required'=>false, 'label'=>'rotavirus-form.doses'))
            ->add('firstVaccinationDose',       'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.firstVaccinationDose'))
            ->add('secondVaccinationDose',      'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.secondVaccinationDose'))
            ->add('thirdVaccinationDose',       'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.thirdVaccinationDose'))
            ->add('stoolCollected',             'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolCollected'))
            ->add('stoolId',                    null,               array('required'=>false, 'label'=>'rotavirus-form.stoolId'))
            ->add('stoolCollectionDate',        'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolCollectionDate'))
            ->add('dischargeOutcome',           'DischargeOutcome', array('required'=>false, 'label'=>'rotavirus-form.dischargeOutcome'))
            ->add('dischargeDate',              'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.dischargeDate'))
            ->add('dischargeClassOther',        null,               array('required'=>false, 'label'=>'rotavirus-form.dischargeClassOther'))
            ->add('comment',                    null,               array('required'=>false, 'label'=>'rotavirus-form.comment'))
        ;

        $factory        = $builder->getFormFactory();
        $siteSerializer = $this->siteSerializer;
        $em             = $this->em;

        $builder->addEventListener(
                        FormEvents::PRE_SET_DATA,
                        function(FormEvent $event) use($factory,$siteSerializer,$em)
                        {
                            $form  = $event->getForm();

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
            'data_class' => 'NS\SentinelBundle\Entity\RotaVirus'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rotavirus';
    }
}
