<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MeningitisType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dob',null,array('required'=>false,'label'=>'form.date-of-birth'))
            ->add('ageInMonths',null,array('required'=>false,'label'=>'form.age-in-months'))
            ->add('hibReceived','triple_choice',array('required'=>false,'label'=>'form.hib-received'))
            ->add('hibDoses','Doses',array('required'=>false,'label'=>'form.hib-doses'))
            ->add('pcvReceived','triple_choice',array('required'=>false))
            ->add('pcvDoses','Doses',array('required'=>false))
            ->add('meningReceived','triple_choice',array('required'=>false))
            ->add('meningDoses','Doses',array('required'=>false))
            ->add('dtpReceived','triple_choice',array('required'=>false))
            ->add('dtpDoses','Doses',array('required'=>false))
            ->add('admDate','datepicker',array('required'=>false))
            ->add('admDx','Diagnosis',array('required'=>false))
            ->add('admDxOther',null,array('required'=>false))
            ->add('menSeizures','triple_choice',array('required'=>false))
            ->add('menFever','triple_choice',array('required'=>false))
            ->add('menAltConscious','triple_choice',array('required'=>false))
            ->add('menInabilityFeed','triple_choice',array('required'=>false))
            ->add('menStridor','triple_choice',array('required'=>false))
            ->add('menNeckStiff','triple_choice',array('required'=>false))
            ->add('menRash','triple_choice',array('required'=>false))
            ->add('menFontanelleBulge','triple_choice',array('required'=>false))
            ->add('menLethargy','triple_choice',array('required'=>false))
            ->add('menPoorSucking','triple_choice',array('required'=>false))
            ->add('menIrritability','triple_choice',array('required'=>false))
            ->add('menSymptomOther',null,array('required'=>false))
            ->add('pneuDiffBreathe','triple_choice',array('required'=>false))
            ->add('pneuChestIndraw','triple_choice',array('required'=>false))
            ->add('pneuCough','triple_choice',array('required'=>false))
            ->add('pneuCyanosis','triple_choice',array('required'=>false))
            ->add('pneuRespRate',null,array('required'=>false))
            ->add('pneuSymptomOther',null,array('required'=>false))
            ->add('csfCollected',null,array('required'=>false))
            ->add('csfCollectDateTime','dateclockpicker',array('required'=>false))
            ->add('csfAppearance','CSFAppearance',array('required'=>false))
            ->add('csfLabDateTime','dateclockpicker',array('required'=>false))
            ->add('bloodCollected',null, array('required'=>false))
            ->add('csfWcc',null,array('required'=>false))
            ->add('csfGlucose',null,array('required'=>false))
            ->add('csfProtein',null,array('required'=>false))
            ->add('csfCultDone','triple_choice',array('required'=>false))
            ->add('csfGramDone','triple_choice',array('required'=>false))
            ->add('csfBinaxDone','triple_choice',array('required'=>false))
            ->add('csfLatDone','triple_choice',array('required'=>false))
            ->add('csfPcrDone','triple_choice',array('required'=>false))
            ->add('bloodCultDone','triple_choice',array('required'=>false))
            ->add('bloodGramDone','triple_choice',array('required'=>false))
            ->add('bloodPcrDone','triple_choice',array('required'=>false))
            ->add('otherCultDone','triple_choice',array('required'=>false))
            ->add('otherTestDone','triple_choice',array('required'=>false))
            ->add('csfCultOther',null,array('required'=>false))
            ->add('rrlCsfDate','dateclockpicker',array('required'=>false))
            ->add('rrlIsolDate','datepicker',array('required'=>false))
            ->add('rrlName',null,array('required'=>false))
            ->add('spnSerotype',null,array('required'=>false))
            ->add('hiSerotype',null,array('required'=>false))
            ->add('nmSerogroup',null,array('required'=>false))
            ->add('cxrDone','triple_choice',array('required'=>false))
            ->add('cxrResult','CXRResult',array('required'=>false))
            ->add('dischOutcome','DischargeOutcome',array('required'=>false))
            ->add('dischDx','Diagnosis',array('required'=>false))
            ->add('dischSequelae','triple_choice',array('required'=>false))
            ->add('comment',null,array('required'=>false))
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
        return 'ns_sentinelbundle_meningitis';
    }
}
