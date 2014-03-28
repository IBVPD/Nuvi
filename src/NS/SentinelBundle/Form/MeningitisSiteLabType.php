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
            ->add('csfLabDateTime','acedatetime',array('required'=>false,'label'=>'meningitis-form.csf-lab-datetime'))
            ->add('csfWcc',null,array('required'=>false,'label'=>'meningitis-form.csf-wcc'))
            ->add('csfGlucose',null,array('required'=>false,'label'=>'meningitis-form.csf-glucose'))
            ->add('csfProtein',null,array('required'=>false,'label'=>'meningitis-form.csf-protein'))
            ->add('csfCultDone','TripleChoice',array('required'=>false,'label'=>'meningitis-form.csf-cult-done'))
            ->add('csfGramDone','TripleChoice',array('required'=>false,'label'=>'meningitis-form.csf-gram-done'))
            ->add('csfBinaxDone','TripleChoice',array('required'=>false,'label'=>'meningitis-form.csf-binax-done'))
            ->add('csfLatDone','TripleChoice',array('required'=>false,'label'=>'meningitis-form.csf-lat-done'))
            ->add('csfPcrDone','TripleChoice',array('required'=>false,'label'=>'meningitis-form.csf-pcr-done'))
            ->add('csfCultResult','LatResult',array('required'=>false, 'label'=>'meningitis-form.csf-cult-result'))
            ->add('csfCultOther',null,array('required'=>false,'label'=>'meningitis-form.csf-culture-other'))
            ->add('csfGramResult','GramStain',array('required'=>false, 'label'=>'meningitis-form.csf-gram-result'))
            ->add('csfGramResultOrganism','GramStainOrganism',array('required'=>false, 'label'=>'meningitis-form.csf-gram-result-organism'))
            ->add('csfGramOther',null,array('required'=>false, 'label'=>'meningitis-form.csf-gram-other'))
            ->add('csfBinaxResult','BinaxResult',array('required'=>false, 'label'=>'meningitis-form.csf-binax-result'))
            ->add('csfLatResult','LatResult',array('required'=>false, 'label'=>'meningitis-form.csf-lat-result'))
            ->add('csfLatOther',null,array('required'=>false, 'label'=>'meningitis-form.csf-lat-other'))
            ->add('csfPcrResult','PCRResult',array('required'=>false, 'label'=>'meningitis-form.csf-pcr-result'))
            ->add('csfPcrOther',null,array('required'=>false, 'label'=>'meningitis-form.csf-pcr-other'))
            ->add('rrlCsfDate','acedatepicker',array('required'=>false,'label'=>'meningitis-form.rrl-csf-date'))
            ->add('rrlIsolDate','acedatepicker',array('required'=>false,'label'=>'meningitis-form.rrl-isol-date'))
            ->add('rrlIsolBloodDate','acedatepicker',array('required'=>false, 'label'=>'meningitis-form.rrl-isol-blood-date'))
            ->add('rrlBrothDate','acedatepicker',array('required'=>false, 'label'=>'meningitis-form.rrl-broth-date'))
            
            ->add('csfStore','TripleChoice',array('required'=>false,'label'=>'meningitis-form.csf-store'))
            ->add('isolStore','TripleChoice',array('required'=>false,'label'=>'meningitis-form.isol-store'))
            ->add('rrlName',null,array('required'=>false,'label'=>'meningitis-form.rrl-name'))
            ->add('spnSerotype',null,array('required'=>false,'label'=>'meningitis-form.spn-serotype'))
            ->add('hiSerotype',null,array('required'=>false,'label'=>'meningitis-form.hi-serotype'))
            ->add('nmSerogroup',null,array('required'=>false,'label'=>'meningitis-form.nm-serogroup'))

            ->add('bloodCultDone','TripleChoice',array('required'=>false,'label'=>'meningitis-form.blood-cult-done'))
            ->add('bloodGramDone','TripleChoice',array('required'=>false,'label'=>'meningitis-form.blood-gram-done'))
            ->add('bloodPcrDone','TripleChoice',array('required'=>false,'label'=>'meningitis-form.blood-pcr-done'))
            ->add('otherCultDone','TripleChoice',array('required'=>false,'label'=>'meningitis-form.other-culture'))
            ->add('otherTestDone','TripleChoice',array('required'=>false,'label'=>'meningitis-form.other-test-done'))
            ->add('otherTest',null,array('required'=>false,'label'=>'meningitis-form.other-test'))
            ->add('bloodCultResult','LatResult',array('required'=>false,'label'=>'meningitis-form.blood-cult-result'))
            ->add('bloodCultOther',null,array('required'=>false,'label'=>'meningitis-form.blood-cult-other'))
            ->add('bloodGramResult','GramStain',array('required'=>false,'label'=>'meningitis-form.blood-gram-result'))
            ->add('bloodGramResultOrganism','GramStainOrganism',array('required'=>false,'label'=>'meningitis-form.blood-gram-result-organism'))
            ->add('bloodGramOther',null,array('required'=>false,'label'=>'meningitis-form.blood-gram-other'))
            ->add('bloodPcrResult','PCRResult',array('required'=>false,'label'=>'meningitis-form.blood-pcr-result'))
            ->add('bloodPcrOther',null,array('required'=>false,'label'=>'meningitis-form.blood-pcr-other'))
            ->add('otherCultResult','LatResult',array('required'=>false,'label'=>'meningitis-form.other-tests'))
            ->add('otherCultOther',null,array('required'=>false,'label'=>'meningitis-form.other-cult-other'))
            ->add('otherTestResult','PCRResult',array('required'=>false,'label'=>'meningitis-form.other-test-result'))
            ->add('otherTestOther',null,array('required'=>false,'label'=>'meningitis-form.other-test-other'))
            ->add('cxrDone','TripleChoice',array('required'=>false,'label'=>'meningitis-form.cxr-done'))
            ->add('cxrResult','CXRResult',array('required'=>false,'label'=>'meningitis-form.cxr-result'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\SiteLab'
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
