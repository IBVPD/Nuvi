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

class MeningitisType extends AbstractType
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
            ->add('dob',null,array('required'=>false,'label'=>'meningitis-form.date-of-birth'))
            ->add('gender','Gender',array('required'=>false,'label'=>'meningitis-form.gender'))
            ->add('hibReceived','triple_choice',array('required'=>false,'label'=>'meningitis-form.hib-received'))
            ->add('hibDoses','Doses',array('required'=>false,'label'=>'meningitis-form.hib-doses'))
            ->add('pcvReceived','triple_choice',array('required'=>false,'label'=>'meningitis-form.pcv-received'))
            ->add('pcvDoses','Doses',array('required'=>false,'label'=>'meningitis-form.pcv-doses'))
            ->add('meningReceived','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-received'))
            ->add('meningDoses','Doses',array('required'=>false,'label'=>'meningitis-form.men-doses'))
            ->add('dtpReceived','triple_choice',array('required'=>false,'label'=>'meningitis-form.dtp-received'))
            ->add('dtpDoses','Doses',array('required'=>false,'label'=>'meningitis-form.dtp-doses'))
            ->add('admDate','datepicker',array('required'=>false,'label'=>'meningitis-form.adm-date'))
            ->add('admDx','Diagnosis',array('required'=>false,'label'=>'meningitis-form.adm-dx'))
            ->add('admDxOther',null,array('required'=>false,'label'=>'meningitis-form.adm-dx-other'))
            ->add('menSeizures','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-seizures'))
            ->add('menFever','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-fever'))
            ->add('menAltConscious','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-alt-conscious'))
            ->add('menInabilityFeed','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-inability-feed'))
            ->add('menStridor','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-stridor'))
            ->add('menNeckStiff','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-stiff-neck'))
            ->add('menRash','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-rash'))
            ->add('menFontanelleBulge','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-fontanelle-bulge'))
            ->add('menLethargy','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-lethargy'))
            ->add('menPoorSucking','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-poor-sucky'))
            ->add('menIrritability','triple_choice',array('required'=>false,'label'=>'meningitis-form.men-irritability'))
            ->add('menSymptomOther',null,array('required'=>false,'label'=>'meningitis-form.men-symptom-other'))
            ->add('pneuDiffBreathe','triple_choice',array('required'=>false,'label'=>'meningitis-form.pneu-diff-breathe'))
            ->add('pneuChestIndraw','triple_choice',array('required'=>false,'label'=>'meningitis-form.pneu-chest-indraw'))
            ->add('pneuCough','triple_choice',array('required'=>false,'label'=>'meningitis-form.pneu-cough'))
            ->add('pneuCyanosis','triple_choice',array('required'=>false,'label'=>'meningitis-form.pneu-cyanosis'))
            ->add('pneuRespRate',null,array('required'=>false,'label'=>'meningitis-form.pneu-resp-rate'))
            ->add('pneuSymptomOther',null,array('required'=>false,'label'=>'meningitis-form.pneu-symptom-other'))
            ->add('csfCollected',null,array('required'=>false,'label'=>'meningitis-form.csf-collected'))
            ->add('csfCollectDateTime','dateclockpicker',array('required'=>false,'label'=>'meningitis-form.csf-collect-datetime'))
            ->add('csfAppearance','CSFAppearance',array('required'=>false,'label'=>'meningitis-form.csf-appearance'))
            ->add('csfLabDateTime','dateclockpicker',array('required'=>false,'label'=>'meningitis-form.csf-lab-datetime'))
            ->add('bloodCollected',null, array('required'=>false,'label'=>'meningitis-form.blood-collected'))
            ->add('csfWcc',null,array('required'=>false,'label'=>'meningitis-form.csf-wcc'))
            ->add('csfGlucose',null,array('required'=>false,'label'=>'meningitis-form.csf-glucose'))
            ->add('csfProtein',null,array('required'=>false,'label'=>'meningitis-form.csf-protein'))
            ->add('csfCultDone','triple_choice',array('required'=>false,'label'=>'meningitis-form.csf-cult-done'))
            ->add('csfGramDone','triple_choice',array('required'=>false,'label'=>'meningitis-form.csf-gram-done'))
            ->add('csfBinaxDone','triple_choice',array('required'=>false,'label'=>'meningitis-form.csf-binax-done'))
            ->add('csfLatDone','triple_choice',array('required'=>false,'label'=>'meningitis-form.csf-lat-done'))
            ->add('csfPcrDone','triple_choice',array('required'=>false,'label'=>'meningitis-form.csf-pcr-done'))
            ->add('bloodCultDone','triple_choice',array('required'=>false,'label'=>'meningitis-form.blood-cult-done'))
            ->add('bloodGramDone','triple_choice',array('required'=>false,'label'=>'meningitis-form.blood-gram-done'))
            ->add('bloodPcrDone','triple_choice',array('required'=>false,'label'=>'meningitis-form.blood-pcr-done'))
            ->add('otherCultDone','triple_choice',array('required'=>false,'label'=>'meningitis-form.other-culture'))
            ->add('otherTestDone','triple_choice',array('required'=>false,'label'=>'meningitis-form.other-tests'))
            ->add('csfCultOther',null,array('required'=>false,'label'=>'meningitis-form.csf-culture-other'))
            ->add('rrlCsfDate','dateclockpicker',array('required'=>false,'label'=>'meningitis-form.rrl-csf-date'))
            ->add('rrlIsolDate','datepicker',array('required'=>false,'label'=>'meningitis-form.rrl-isol-date'))
            ->add('rrlName',null,array('required'=>false,'label'=>'meningitis-form.rrl-name'))
            ->add('spnSerotype',null,array('required'=>false,'label'=>'meningitis-form.spn-serotype'))
            ->add('hiSerotype',null,array('required'=>false,'label'=>'meningitis-form.hi-serotype'))
            ->add('nmSerogroup',null,array('required'=>false,'label'=>'meningitis-form.nm-serogroup'))
            ->add('cxrDone','triple_choice',array('required'=>false,'label'=>'meningitis-form.cxr-done'))
            ->add('cxrResult','CXRResult',array('required'=>false,'label'=>'meningitis-form.cxr-result'))
            ->add('dischOutcome','DischargeOutcome',array('required'=>false,'label'=>'meningitis-form.discharge-outcome'))
            ->add('dischDx','Diagnosis',array('required'=>false,'label'=>'meningitis-form.discharge-diagnosis'))
            ->add('dischSequelae','triple_choice',array('required'=>false,'label'=>'meningitis-form.discharge-sequelae'))
            ->add('comment',null,array('required'=>false,'label'=>'meningitis-form.comment'))
        ;
        
        $factory = $builder->getFormFactory();
        $em = &$this->em;
        $sc = &$this->securityContext;
        $se = &$this->session;

        $builder->addEventListener(
                        FormEvents::PRE_SET_DATA,
                        function(FormEvent $event) use($factory,$builder,$sc,$se,$em)
                        {
                            $form        = $event->getForm();
                            $data        = $event->getData();
                            $user        = $sc->getToken()->getUser();
                            $sites       = $se->get('sites',array());
                            
                            if(!$data->getId())
                            {
                                if(count($sites) == 0)
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
                                                                                              'label' => 'meningitis-form.site', 
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
                            if($data->getId() > 0)// no editing of sites
                                return;
                            
                            $sites = $se->get('sites',array()); //should be array with single site
                            $site = array_pop($sites);

                            if(!$em->contains($site))
                            {
                                $uow = $em->getUnitOfWork();
                                $c = $site->getCountry();
                                $r = $c->getRegion();

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
