<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use NS\SentinelBundle\Form\Types\Role;

class RotaVirusType extends AbstractType
{
    private $securityContext;
    private $session;
    private $em;

    public function __construct(SecurityContextInterface $context, Session $session, ObjectManager $em)
    {
        $this->securityContext = $context;
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
            ->add('caseId',                     null,               array('required'=>false, 'label'=>'rotavirus-form.caseId'))
            ->add('gender',                     'Gender',           array('required'=>false, 'label'=>'rotavirus-form.gender'))
            ->add('dob',                        'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.dob'))
            ->add('district',                   null,               array('required'=>false, 'label'=>'rotavirus-form.district'))
            ->add('admissionDate',              'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.admissionDate'))
            ->add('symptomDiarrhea',            'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrhea'))
            ->add('symptomDiarrheaOnset',       null,               array('required'=>false, 'label'=>'rotavirus-form.symptomDiarrheaOnset'))
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

        $factory = $builder->getFormFactory();
        $em = &$this->em;
        $sc = &$this->securityContext;
        $se = &$this->session;

        $builder->addEventListener(
                        FormEvents::PRE_SET_DATA,
                        function(FormEvent $event) use($factory,$sc,$se,$em)
                        {
                            $form        = $event->getForm();
                            $data        = $event->getData();
                            $user        = $sc->getToken()->getUser();
                            $sites       = $se->get('sites',array());

                            if(!$data) // new object
                            {
                                if(count($sites) == 0) // empty session site array so build and store
                                {
                                    foreach($user->getAcls() as $acl)
                                    {
                                        if($acl->getType()->equal(Role::SITE))
                                            $sites[] = $acl->getObjectId();
                                    }

                                    $sites = $em->getRepository('NSSentinelBundle:Site')->getChain((count($sites) > 1) ? $sites : array_pop($sites));

                                    $se->set('sites',$sites);
                                }

                                if($user->getAcls()->count() > 1)
                                {
                                    $transformer = new \NS\SentinelBundle\Form\Transformer\IdToReference($em,$se);
                                    $form->add($factory->createNamed('site','choice',null,array('required' => true,
                                                                                              'empty_value' => 'Please Select...',
                                                                                              'label' => 'rotavirus-form.site',
                                                                                              'choices' => $sites,
                                                                                              'auto_initialize' => false)));
                                }
                            }
                        }
            );
        $builder->addEventListener(
                        FormEvents::SUBMIT,
                        function(FormEvent $event) use ($sc,$se,$em)
                        {
                            $user = $sc->getToken()->getUser();
                            if($user->getAcls()->count() > 1) // they'll be choosing so exit
                                return;

                            $data = $event->getData();
                            if(!$data || $data->hasId()) // no editing of sites
                                return;

                            $sites = $se->get('sites',array()); // should be array with single site
                            $site = array_pop($sites);

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
        ;
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
