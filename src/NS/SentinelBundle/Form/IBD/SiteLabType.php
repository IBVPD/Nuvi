<?php

namespace NS\SentinelBundle\Form\IBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use NS\SentinelBundle\Services\SerializedSites;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\LatResult;
use NS\SentinelBundle\Form\Types\SpnSerotype;
use NS\SentinelBundle\Form\Types\HiSerotype;
use NS\SentinelBundle\Form\Types\NmSerogroup;
use NS\SentinelBundle\Form\Types\PCRResult;
use NS\SentinelBundle\Entity\Country;

class SiteLabType extends AbstractType
{
    private $siteSerializer;

    public function __construct(SerializedSites $siteSerializer)
    {
        $this->siteSerializer = $siteSerializer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csfLabDateTime',     'acedatetime',          array('required'=>false, 'label'=>'meningitis-form.csf-lab-datetime'))
            ->add('csfWcc',             null,                   array('required'=>false, 'label'=>'meningitis-form.csf-wcc'))
            ->add('csfGlucose',         null,                   array('required'=>false, 'label'=>'meningitis-form.csf-glucose'))
            ->add('csfProtein',         null,                   array('required'=>false, 'label'=>'meningitis-form.csf-protein'))
            ->add('csfCultDone',        'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csf-cult-done',      'attr' => array('data-context-child'=>'csfCultDone')))
            ->add('csfCultResult',      'LatResult',            array('required'=>false, 'label'=>'meningitis-form.csf-cult-result',    'attr' => array('data-context-parent'=>'csfCultDone', 'data-context-child'=>'csfCultDoneOther', 'data-context-value'=> TripleChoice::YES)))
            ->add('csfCultOther',       null,                   array('required'=>false, 'label'=>'meningitis-form.csf-culture-other',  'attr' => array('data-context-parent'=>'csfCultDoneOther', 'data-context-value'=> LatResult::OTHER)))

            ->add('csfGramDone',        'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csf-gram-done',            'attr' => array('data-context-child'=>'csfGramDone')))
            ->add('csfGramResult',      'GramStain',            array('required'=>false, 'label'=>'meningitis-form.csf-gram-result',          'attr' => array('data-context-parent'=>'csfGramDone', 'data-context-value'=> TripleChoice::YES)))
            ->add('csfGramResultOrganism','GramStainOrganism',  array('required'=>false, 'label'=>'meningitis-form.csf-gram-result-organism', 'attr' => array('data-context-parent'=>'csfGramDone', 'data-context-value'=> TripleChoice::YES)))
            ->add('csfGramOther',       null,                   array('required'=>false, 'label'=>'meningitis-form.csf-gram-other',           'attr' => array('data-context-parent'=>'csfGramDone', 'data-context-value'=> TripleChoice::YES)))

            ->add('csfBinaxDone',       'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csf-binax-done',     'attr' => array('data-context-child'=>'csfBinaxDone')))
            ->add('csfBinaxResult',     'BinaxResult',          array('required'=>false, 'label'=>'meningitis-form.csf-binax-result',   'attr' => array('data-context-parent'=>'csfBinaxDone', 'data-context-value'=> TripleChoice::YES)))

            ->add('csfLatDone',         'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csf-lat-done',    'attr' => array('data-context-child'=>'csfLatDone')))
            ->add('csfLatResult',       'LatResult',            array('required'=>false, 'label'=>'meningitis-form.csf-lat-result',  'attr' => array('data-context-parent'=>'csfLatDone', 'data-context-value'=> TripleChoice::YES)))
            ->add('csfLatOther',        null,                   array('required'=>false, 'label'=>'meningitis-form.csf-lat-other',   'attr' => array('data-context-parent'=>'csfLatDone', 'data-context-value'=> TripleChoice::YES)))

            ->add('csfPcrDone',         'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csf-pcr-done',    'attr' => array('data-context-child'=>'csfPcrDone')))
            ->add('csfPcrResult',       'PCRResult',            array('required'=>false, 'label'=>'meningitis-form.csf-pcr-result',  'attr' => array('data-context-parent'=>'csfPcrDone','data-context-child'=>'csfPcrDoneResult',  'data-context-value'=> TripleChoice::YES)))
            ->add('csfPcrOther',        null,                   array('required'=>false, 'label'=>'meningitis-form.csf-pcr-other',   'attr' => array('data-context-parent'=>'csfPcrDoneResult', 'data-context-value'=> PCRResult::OTHER)))

            ->add('rrlCsfDate',         'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.rrl-csf-date'))
            ->add('rrlIsolDate',        'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.rrl-isol-date'))
            ->add('rrlIsolBloodDate',   'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.rrl-isol-blood-date'))
            ->add('rrlBrothDate',       'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.rrl-broth-date'))

            ->add('csfStore',           'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csf-store'))
            ->add('isolStore',          'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.isol-store'))
            ->add('spnSerotype',        'SpnSerotype',          array('required'=>false, 'label'=>'meningitis-form.spn-serotype',       'attr' => array('data-context-child'=>'spnSerotype')))
            ->add('spnSerotypeOther',   null,                   array('required'=>false, 'label'=>'meningitis-form.spn-serotype-other', 'attr' => array('data-context-parent'=>'spnSerotype','data-context-value'=> SpnSerotype::OTHER)))
            ->add('hiSerotype',         'HiSerotype',           array('required'=>false, 'label'=>'meningitis-form.hi-serotype',       'attr' => array('data-context-child'=>'hiSerotype')))
            ->add('hiSerotypeOther',    null,                   array('required'=>false, 'label'=>'meningitis-form.hi-serotype-other', 'attr' => array('data-context-parent'=>'hiSerotype','data-context-value'=>  HiSerotype::OTHER)))
            ->add('nmSerogroup',        'NmSerogroup',          array('required'=>false, 'label'=>'meningitis-form.nm-serogroup',       'attr' => array('data-context-child'=>'nmSerogroup')))
            ->add('nmSerogroupOther',   null,                   array('required'=>false, 'label'=>'meningitis-form.nm-serogroup-other', 'attr' => array('data-context-parent'=>'nmSerogroup','data-context-value'=> NmSerogroup::OTHER)))

            ->add('bloodCultDone',      'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.blood-cult-done',    'attr' => array('data-context-child'=>'bloodCultDone')))
            ->add('bloodCultResult',    'LatResult',            array('required'=>false, 'label'=>'meningitis-form.blood-cult-result',  'attr' => array('data-context-parent'=>'bloodCultDone','data-context-value'=> TripleChoice::YES)))
            ->add('bloodCultOther',     null,                   array('required'=>false, 'label'=>'meningitis-form.blood-cult-other',   'attr' => array('data-context-parent'=>'bloodCultDone','data-context-value'=> TripleChoice::YES)))

            ->add('bloodGramDone',      'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.blood-gram-done',            'attr' => array('data-context-child'=>'bloodGramDone')))
            ->add('bloodGramResult',    'GramStain',            array('required'=>false, 'label'=>'meningitis-form.blood-gram-result',          'attr' => array('data-context-parent'=>'bloodGramDone','data-context-value'=> TripleChoice::YES)))
            ->add('bloodGramResultOrganism','GramStainOrganism',array('required'=>false, 'label'=>'meningitis-form.blood-gram-result-organism', 'attr' => array('data-context-parent'=>'bloodGramDone','data-context-value'=> TripleChoice::YES)))
            ->add('bloodGramOther',     null,                   array('required'=>false, 'label'=>'meningitis-form.blood-gram-other',           'attr' => array('data-context-parent'=>'bloodGramDone','data-context-value'=> TripleChoice::YES)))

            ->add('bloodPcrDone',       'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.blood-pcr-done',    'attr' => array('data-context-child'=>'bloodPcrDone')))
            ->add('bloodPcrResult',     'PCRResult',            array('required'=>false, 'label'=>'meningitis-form.blood-pcr-result',  'attr' => array('data-context-parent'=>'bloodPcrDone','data-context-value'=> TripleChoice::YES)))
            ->add('bloodPcrOther',      null,                   array('required'=>false, 'label'=>'meningitis-form.blood-pcr-other',   'attr' => array('data-context-parent'=>'bloodPcrDone','data-context-value'=> TripleChoice::YES)))

            ->add('otherCultDone',      'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.other-cult-done',    'attr'=>array('data-context-child'=>'otherCultDone')))
            ->add('otherCultResult',    'LatResult',            array('required'=>false, 'label'=>'meningitis-form.other-cult-result',  'attr'=>array('data-context-parent'=>'otherCultDone','data-context-value'=> TripleChoice::YES)))
            ->add('otherCultOther',     null,                   array('required'=>false, 'label'=>'meningitis-form.other-cult-other',   'attr'=>array('data-context-parent'=>'otherCultDone','data-context-value'=> TripleChoice::YES)))

            ->add('otherTestDone',      'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.other-test-done',    'attr'=>array('data-context-child'=>'otherTestDone')))
            ->add('otherTestResult',    'PCRResult',            array('required'=>false, 'label'=>'meningitis-form.other-test-result',  'attr'=>array('data-context-parent'=>'otherTestDone','data-context-value'=> TripleChoice::YES)))
            ->add('otherTestOther',     null,                   array('required'=>false, 'label'=>'meningitis-form.other-test-other',   'attr'=>array('data-context-parent'=>'otherTestDone','data-context-value'=> TripleChoice::YES)))
            ->add('otherTest',          null,                   array('required'=>false, 'label'=>'meningitis-form.other-test',         'attr'=>array('data-context-parent'=>'otherTestDone','data-context-value'=> TripleChoice::YES)))
        ;

        $siteSerializer = $this->siteSerializer;
        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use($siteSerializer)
                {
                    $data    = $event->getData();
                    $form    = $event->getForm();
                    $country = null;

                    if($data && $data->getCase() && $data->getCase()->getCountry())
                        $country = $data->getCase()->getCountry();
                    else if(!$siteSerializer->hasMultipleSites())
                    {
                        $site    = $siteSerializer->getSite();
                        $country = ($site instanceof \NS\SentinelBundle\Entity\Site) ? $site->getCountry():null;
                    }

                    if($country instanceof Country)
                    {
                        if($country->hasReferenceLab())
                            $form->add('sentToReferenceLab','switch',array('required'=>false));

                        if($country->hasNationalLab())
                            $form->add('sentToNationalLab','switch',array('required'=>false));
                    }
                });
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\IBD\SiteLab'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ibd_sitelab';
    }
}
