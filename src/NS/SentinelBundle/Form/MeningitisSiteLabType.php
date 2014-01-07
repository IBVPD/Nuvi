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

class MeningitisSiteLabType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csfLabDateTime','dateclockpicker',array('required'=>false,'label'=>'meningitis-form.csf-lab-datetime'))
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
        ;
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
        return 'meningitis_sitelab';
    }
}
