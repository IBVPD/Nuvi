<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use NS\UtilBundle\Form\Extension\ChoiceValue;

class MeningitisType extends AbstractType
{
    private $session;
    private $em;

    public function __construct(Session $session, ObjectManager $em)
    {
        $this->session         = $session;
        $this->em              = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dob','acedatepicker',array('required'=>false,'label'=>'meningitis-form.date-of-birth','widget'=>'single_text'))
            ->add('ageInMonths',null,array('required'=>false,'label'=>'meningitis-form.age-in-months'))
            ->add('gender','Gender',array('required'=>false,'label'=>'meningitis-form.gender'))
            ->add('district',null,array('required'=>false,'label'=>'meningitis-form.district'))
            ->add('caseId',null,array('required'=>false,'label'=>'meningitis-form.case-id'))
            ->add('admDate','acedatepicker',array('required'=>false,'label'=>'meningitis-form.adm-date'))
            ->add('admDx',          'Diagnosis',    array('required'=>false,'label'=>'meningitis-form.adm-dx',       'attr' => array('data-context-group'=>'admissionDiagnosis'), 'special_values'=>array(new ChoiceValue(4,'admissionDiagnosisOther'))))
            ->add('admDxOther',     null,           array('required'=>false,'label'=>'meningitis-form.adm-dx-other', 'attr' => array('data-context-group'=>'admissionDiagnosis', 'data-context-field'=>'admissionDiagnosisOther')))
            ->add('onsetDate','acedatepicker',array('required'=>false,'label'=>'meningitis-form.onset-date'))
            ->add('antibiotics','TripleChoice',array('required'=>false,'label'=>'meningitis-form.antibiotics'))

            ->add('menSeizures','TripleChoice',array('required'=>false,'label'=>'meningitis-form.men-seizures'))
            ->add('menFever','TripleChoice',array('required'=>false,'label'=>'meningitis-form.men-fever'))
            ->add('menAltConscious','TripleChoice',array('required'=>false,'label'=>'meningitis-form.men-alt-conscious'))
            ->add('menInabilityFeed','TripleChoice',array('required'=>false,'label'=>'meningitis-form.men-inability-feed'))
            ->add('menNeckStiff','TripleChoice',array('required'=>false,'label'=>'meningitis-form.men-stiff-neck'))
            ->add('menRash','TripleChoice',array('required'=>false,'label'=>'meningitis-form.men-rash'))
            ->add('menFontanelleBulge','TripleChoice',array('required'=>false,'label'=>'meningitis-form.men-fontanelle-bulge'))
            ->add('menLethargy','TripleChoice',array('required'=>false,'label'=>'meningitis-form.men-lethargy'))

            ->add('hibReceived','TripleChoice',array('required'=>false,'label'=>'meningitis-form.hib-received'))
            ->add('hibDoses','Doses',array('required'=>false,'label'=>'meningitis-form.hib-doses'))
            ->add('pcvReceived','TripleChoice',array('required'=>false,'label'=>'meningitis-form.pcv-received'))
            ->add('pcvDoses','Doses',array('required'=>false,'label'=>'meningitis-form.pcv-doses'))
            ->add('meningReceived','MeningitisVaccinationReceived',array('required'=>false,'label'=>'meningitis-form.men-received'))
            ->add('meningType','MeningitisVaccinationType',array('required'=>false,'label'=>'meningitis-form.men-type'))
            ->add('meningMostRecentDose','acedatepicker',array('required'=>false,'label'=>'meningitis-form.meningMostRecentDose'))

            ->add('csfCollected','switch',array('required'=>false,'label'=>'meningitis-form.csf-collected','switchtype'=>2))
            ->add('csfId',null,array('required'=>false,'label'=>'meningitis-form.csf-id'))
            ->add('csfCollectDateTime','acedatetime',array('required'=>false,'label'=>'meningitis-form.csf-collect-datetime'))
            ->add('csfAppearance','CSFAppearance',array('required'=>false,'label'=>'meningitis-form.csf-appearance'))
            ->add('bloodCollected','switch', array('required'=>false,'label'=>'meningitis-form.blood-collected','switchtype'=>2))
            ->add('dischOutcome','DischargeOutcome',array('required'=>false,'label'=>'meningitis-form.discharge-outcome'))
            ->add('dischDx','Diagnosis',array('required'=>false,'label'=>'meningitis-form.discharge-diagnosis'))
            ->add('dischClass','DischargeClassification',array('required'=>false,'label'=>'meningitis-form.discharge-class'))
            ->add('comment',null,array('required'=>false,'label'=>'meningitis-form.comment'))
        ;

        $factory = $builder->getFormFactory();
        $em = $this->em;
        $se = $this->session;

        $builder->addEventListener(
                        FormEvents::PRE_SET_DATA,
                        function(FormEvent $event) use($factory,$se,$em)
                        {
                            $form  = $event->getForm();
                            $sites = unserialize($se->get('sites'));

                            if(!$sites || count($sites) == 0) // empty session site array so build and store
                            {
                                $sites = $em->getRepository('NS\SentinelBundle\Entity\Site')->getChain();

                                $se->set('sites',serialize($sites));
                            }

                            if(count($sites) > 1)
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

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use($se)
                {
                    $data    = $event->getData();
                    $form    = $event->getForm();
                    $sites   = unserialize($se->get('sites'));
                    $country = null;

                    if($data && $data->getCountry())
                        $country = $data->getCountry();
                    else if(count($sites) == 1)
                    {
                        $site = array_pop($sites);
                        $country = $site->getCountry();
                    }

                    if(!$country || ($country instanceof \NS\SentinelBundle\Entity\Country && $country->getTracksPneumonia()))
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
                });

        $builder->addEventListener(
                        FormEvents::SUBMIT,
                        function(FormEvent $event) use ($se,$em)
                        {
                            $sites = unserialize($se->get('sites'));

                            if($sites && count($sites) > 1) // they'll be choosing so exit
                                return;

                            $data = $event->getData();
                            if(!$data || $data->hasId()) // no editing of sites
                                return;

                            // current gets us the one site we are able to see since we test for count > 1 above
                            $site = current($sites);

                            if(!$em->contains($site))
                            {
                                $uow = $em->getUnitOfWork();
                                $c   = $site->getCountry();
                                $r   = $c->getRegion();

                                $uow->registerManaged($site,array('id'=>$site->getId()),array('id'=>$site->getId(),'code'=>$site->getCode()));
                                $uow->registerManaged($c,array('id'=>$c->getId()),array('id'=>$c->getId(),'code'=>$c->getCode()));
                                $uow->registerManaged($r,array('id'=>$r->getId()),array('id'=>$r->getId(),'code'=>$r->getCode()));

                                $data->setSite($site);
                            }

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
