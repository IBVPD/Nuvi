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
            ->add('dob',null,array('required'=>false,'label'=>'meningitis-form.date-of-birth'))
            ->add('ageInMonths',null,array('required'=>false,'label'=>'meningitis-form.age-in-months'))
            ->add('gender',null,array('required'=>false,'label'=>'meningitis-form.gender'))
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
