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
            ->add('dob')
            ->add('ageInMonths')
            ->add('hibReceived')
            ->add('hibDoses')
            ->add('pcvReceived')
            ->add('pcvDoses')
            ->add('meningReceived')
            ->add('meningDoses')
            ->add('dtpReceived')
            ->add('dtpDoses')
            ->add('admDate')
            ->add('admDx')
            ->add('admDxOther')
            ->add('menSeizures')
            ->add('menFever')
            ->add('menAltConscious')
            ->add('menInabilityFeed')
            ->add('menStridor')
            ->add('menNeckStiff')
            ->add('menRash')
            ->add('menFontanelleBulge')
            ->add('menLethargy')
            ->add('menPoorSucking')
            ->add('menIrritability')
            ->add('menSymptomOther')
            ->add('pneuDiffBreathe')
            ->add('pneuChestIndraw')
            ->add('pneuCough')
            ->add('pneuCyanosis')
            ->add('pneuRespRate')
            ->add('pneuSymptomOther')
            ->add('csfCollected')
            ->add('csfCollectDateTime')
            ->add('csfAppearance')
            ->add('csfLabDateTime')
            ->add('bloodCollected')
            ->add('csfWcc')
            ->add('csfGlucose')
            ->add('csfProtein')
            ->add('csfCultDone')
            ->add('csfGramDone')
            ->add('csfBinaxDone')
            ->add('csfLatDone')
            ->add('csfPcrDone')
            ->add('bloodCultDone')
            ->add('bloodGramDone')
            ->add('bloodPcrDone')
            ->add('otherCultDone')
            ->add('otherTestDone')
            ->add('csfCultOther')
            ->add('rrlCsfDate')
            ->add('rrlIsolDate')
            ->add('rrlName')
            ->add('spnSerotype')
            ->add('hiSerotype')
            ->add('nmSerogroup')
            ->add('cxrDone')
            ->add('cxrResult')
            ->add('dischOutcome')
            ->add('dischDx')
            ->add('dischSequelae')
            ->add('comment')
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
