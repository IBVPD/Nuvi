<?php

namespace NS\SentinelBundle\Form\IBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use NS\SentinelBundle\Interfaces\SerializedSitesInterface;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\CultureResult;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\Types\PCRResult;
use NS\SentinelBundle\Form\Types\GramStain;
use NS\SentinelBundle\Form\Types\GramStainOrganism;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Form\Types\SpnSerotype;
use NS\SentinelBundle\Form\Types\HiSerotype;
use NS\SentinelBundle\Form\Types\NmSerogroup;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class LabType extends AbstractType
{
    private $siteSerializer;

    public function __consturct(SerializedSitesInterface $serializer)
    {
        $this->siteSerializer = $serializer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csfSiteDateTime',     'acedatetime',         array('required'=>false, 'label'=>'meningitis-form.csfSiteDateTime', 'description' => 'This field needs to be sent as two sub fields a csfSiteDateTime[date] and csfSiteDateTime[time]. The time field needs to be 24 clock without seconds.'))
            ->add('csfSiteId',          null,                   array('required'=>false, 'label'=>'meningitis-form.csfSiteId'))

            ->add('csfWcc',             null,                   array('required'=>false, 'label'=>'meningitis-form.csf-wcc'))
            ->add('csfGlucose',         null,                   array('required'=>false, 'label'=>'meningitis-form.csf-glucose'))
            ->add('csfProtein',         null,                   array('required'=>false, 'label'=>'meningitis-form.csf-protein'))
            ->add('csfCultDone',        'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csf-cult-done',     'attr' => array('data-context-child'=>'csfCultDone')))
            ->add('csfCultResult',      'CultureResult',        array('required'=>false, 'label'=>'meningitis-form.csf-cult-result',   'attr' => array('data-context-parent'=>'csfCultDone', 'data-context-child'=>'csfCultDoneOther', 'data-context-value'=> TripleChoice::YES)))
            ->add('csfCultOther',       null,                   array('required'=>false, 'label'=>'meningitis-form.csf-culture-other', 'attr' => array('data-context-parent'=>'csfCultDoneOther', 'data-context-value'=> CultureResult::OTHER)))

            ->add('csfGramDone',        'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csf-gram-done',            'attr' => array('data-context-child'=>'csfGramDone')))
            ->add('csfGramResult',      'GramStain',            array('required'=>false, 'label'=>'meningitis-form.csf-gram-result',          'attr' => array('data-context-parent'=>'csfGramDone',           'data-context-child'=>'csfGramResult',        'data-context-value'=> TripleChoice::YES)))
            ->add('csfGramResultOrganism','GramStainOrganism',  array('required'=>false, 'label'=>'meningitis-form.csf-gram-result-organism', 'attr' => array('data-context-parent'=>'csfGramResult',         'data-context-child'=>'csfGramResultOrganism','data-context-value'=> json_encode(array(GramStain::GM_NEGATIVE,GramStain::GM_POSITIVE)))))
            ->add('csfGramOther',       null,                   array('required'=>false, 'label'=>'meningitis-form.csf-gram-other',           'attr' => array('data-context-parent'=>'csfGramResultOrganism', 'data-context-value'=> GramStainOrganism::OTHER)))

            ->add('csfBinaxDone',       'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csf-binax-done',   'attr' => array('data-context-child'=>'csfBinaxDone')))
            ->add('csfBinaxResult',     'BinaxResult',          array('required'=>false, 'label'=>'meningitis-form.csf-binax-result', 'attr' => array('data-context-parent'=>'csfBinaxDone', 'data-context-value'=> TripleChoice::YES)))

            ->add('csfLatDone',         'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csf-lat-done',    'attr' => array('data-context-child'=>'csfLatDone')))
            ->add('csfLatResult',       'LatResult',            array('required'=>false, 'label'=>'meningitis-form.csf-lat-result',  'attr' => array('data-context-parent'=>'csfLatDone', 'data-context-child'=>'csfLatDoneResult', 'data-context-value'=> TripleChoice::YES)))
            ->add('csfLatOther',        null,                   array('required'=>false, 'label'=>'meningitis-form.csf-lat-other',   'attr' => array('data-context-parent'=>'csfLatDoneResult', 'data-context-value'=> PCRResult::OTHER)))

            ->add('csfPcrDone',         'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csf-pcr-done',    'attr' => array('data-context-child'=>'csfPcrDone')))
            ->add('csfPcrResult',       'PCRResult',            array('required'=>false, 'label'=>'meningitis-form.csf-pcr-result',  'attr' => array('data-context-parent'=>'csfPcrDone','data-context-child'=>'csfPcrDoneResult',  'data-context-value'=> TripleChoice::YES)))
            ->add('csfPcrOther',        null,                   array('required'=>false, 'label'=>'meningitis-form.csf-pcr-other',   'attr' => array('data-context-parent'=>'csfPcrDoneResult', 'data-context-value'=> PCRResult::OTHER)))

            ->add('csfSiteDNAExtractionDate',   'acedatepicker', array('required'=>false, 'label'=>'meningitis-form.csfSiteDNAExtractionDate',  'attr'=>array('data-context-parent'=>'csfIsolateSentToSite','data-context-value'=> TripleChoice::YES)))
            ->add('csfSiteDNAVolume',           null,            array('required'=>false, 'label'=>'meningitis-form.csfSiteDNAVolume',          'attr'=>array('data-context-parent'=>'csfIsolateSentToSite','data-context-value'=> TripleChoice::YES)))

            ->add('csfStore',                   'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csfStore'))
            ->add('csfIsolStore',               'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csfIsolStore'))

            ->add('bloodSiteId',                null,                   array('required'=>false, 'label'=>'meningitis-form.bloodSiteId'))
            ->add('bloodSiteDate',              'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.bloodSiteDateTime'))
            ->add('bloodCultDone',              'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.blood-cult-done',    'attr' => array('data-context-child'=>'bloodCultDone')))
            ->add('bloodCultResult',            'CultureResult',        array('required'=>false, 'label'=>'meningitis-form.blood-cult-result',  'attr' => array('data-context-parent'=>'bloodCultDone', 'data-context-child'=>'bloodCultDoneOther', 'data-context-value'=> TripleChoice::YES)))
            ->add('bloodCultOther',             null,                   array('required'=>false, 'label'=>'meningitis-form.bloodCultOther',   'attr' => array('data-context-parent'=>'bloodCultDoneOther','data-context-value'=> CultureResult::OTHER)))

            ->add('bloodGramDone',              'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.blood-gram-done',            'attr' => array('data-context-child'=>'bloodGramDone')))
            ->add('bloodGramResult',            'GramStain',            array('required'=>false, 'label'=>'meningitis-form.blood-gram-result',          'attr' => array('data-context-parent'=>'bloodGramDone','data-context-child'=>'bloodGramResult',        'data-context-value'=> TripleChoice::YES)))
            ->add('bloodGramResultOrganism',    'GramStainOrganism',    array('required'=>false, 'label'=>'meningitis-form.blood-gram-result-organism', 'attr' => array('data-context-parent'=>'bloodGramResult','data-context-child'=>'bloodGramResultOther', 'data-context-value'=> json_encode(array(GramStain::GM_NEGATIVE,GramStain::GM_POSITIVE)))))
            ->add('bloodGramOther',             null,                   array('required'=>false, 'label'=>'meningitis-form.blood-gram-other',           'attr' => array('data-context-parent'=>'bloodGramResultOther','data-context-child'=>'', 'data-context-value'=> GramStainOrganism::OTHER)))

            ->add('bloodPcrDone',               'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.blood-pcr-done',    'attr' => array('data-context-child'=>'bloodPcrDone')))
            ->add('bloodPcrResult',             'PCRResult',            array('required'=>false, 'label'=>'meningitis-form.blood-pcr-result',  'attr' => array('data-context-parent'=>'bloodPcrDone','data-context-child'=>'bloodPcrDoneResult', 'data-context-value'=> TripleChoice::YES)))
            ->add('bloodPcrOther',              null,                   array('required'=>false, 'label'=>'meningitis-form.blood-pcr-other',   'attr' => array('data-context-parent'=>'bloodPcrDone','data-context-value'=> PCRResult::OTHER)))

            ->add('otherSiteId',                null,                   array('required'=>false, 'label'=>'meningitis-form.otherSiteId'))
            ->add('otherSiteDate',              'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.otherSiteDateTime'))
            ->add('otherCultDone',              'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.other-cult-done1',   'attr'=>array('data-context-child'=>'otherCultDone')))
            ->add('otherCultResult',            'CultureResult',        array('required'=>false, 'label'=>'meningitis-form.other-cult-result',  'attr'=>array('data-context-child'=>'otherCultResult','data-context-parent'=>'otherCultDone','data-context-value'=> TripleChoice::YES)))
            ->add('otherCultOther',             null,                   array('required'=>false, 'label'=>'meningitis-form.otherCultOther',     'attr'=>array('data-context-parent'=>'otherCultResult','data-context-value'=> CultureResult::OTHER)))

            ->add('pathogenIdentifierMethod',   'PathogenIdentifier',   array('label'=>'meningitis-rrl-form.pathogenIdentifierMethod','required'=>false, 'attr' => array('data-context-child'=>'pathogenIdentifierMethod')))
            ->add('pathogenIdentifierOther',    null,                   array('label'=>'meningitis-rrl-form.pathogen-id-other', 'required'=>false, 'attr' => array('data-context-parent'=>'pathogenIdentifierMethod', 'data-context-value'=>PathogenIdentifier::OTHER)))
            ->add('serotypeIdentifier',         'SerotypeIdentifier',   array('label'=>'meningitis-rrl-form.serotype-id-method','required'=>false, 'attr' => array('data-context-child'=>'serotypeIdentifier')))
            ->add('serotypeIdentifierOther',    null,                   array('label'=>'meningitis-rrl-form.serotype-id-other', 'required'=>false, 'attr' => array('data-context-parent'=>'serotypeIdentifier', 'data-context-value'=>SerotypeIdentifier::OTHER)))
            ->add('lytA',                       null,                   array('label'=>'meningitis-rrl-form.lytA','required'=>false))
            ->add('sodC',                       null,                   array('label'=>'meningitis-rrl-form.sodC','required'=>false))
            ->add('hpd',                        null,                   array('label'=>'meningitis-rrl-form.hpd','required'=>false))
            ->add('rNaseP',                     null,                   array('label'=>'meningitis-rrl-form.rNasP','required'=>false))
            ->add('spnSerotype',                'SpnSerotype',          array('label'=>'meningitis-rrl-form.spnSerotype','required'=>false,       'attr' => array('data-context-child'=>'spnSerotype')))
            ->add('spnSerotypeOther',           null,                   array('label'=>'meningitis-rrl-form.spnSerotype-other','required'=>false, 'attr' => array('data-context-parent'=>'spnSerotype', 'data-context-value'=>SpnSerotype::OTHER)))
            ->add('hiSerotype',                 'HiSerotype',           array('label'=>'meningitis-rrl-form.hiSerotype','required'=>false,        'attr' => array('data-context-child'=>'hiSerotype')))
            ->add('hiSerotypeOther',            null,                   array('label'=>'meningitis-rrl-form.hiSerotype-other','required'=>false,  'attr' => array('data-context-parent'=>'hiSerotype', 'data-context-value'=>HiSerotype::OTHER)))
            ->add('nmSerogroup',                'NmSerogroup',          array('label'=>'meningitis-rrl-form.nmSerogroup','required'=>false,       'attr' => array('data-context-child'=>'nmSerogroup')))
            ->add('nmSerogroupOther',           null,                   array('label'=>'meningitis-rrl-form.nmSerogroup-other','required'=>false, 'attr' => array('data-context-parent'=>'nmSerogroup', 'data-context-value'=>NmSerogroup::OTHER)))
        ;

        $siteSerializer = $this->siteSerializer;
        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use($siteSerializer)
                {
                    $data    = $event->getData();
                    $form    = $event->getForm();
                    $country = null;

                    if($data && $data->getCountry())
                        $country = $data->getCountry();
                    else if($siteSerializer && !$siteSerializer->hasMultipleSites())
                    {
                        $site    = $siteSerializer->getSite();
                        $country = ($site instanceof \NS\SentinelBundle\Entity\Site) ? $site->getCountry():null;
                    }

                    if($country instanceof Country)
                    {
                        if($country->hasReferenceLab())
                        {
                            $form->add('csfSentToRRL',              'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csfSentToRRL',           'attr'=>array('data-context-child' =>'csfSentToRRL')))
                                 ->add('csfDateSentToRRL',          'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.csfDateSentToRRL',       'attr'=>array('data-context-parent'=>'csfSentToRRL','data-context-value'=> TripleChoice::YES)))
                                 ->add('csfRRLId',                  null,                   array('required'=>false, 'label'=>'meningitis-form.csfRRLId',               'attr'=>array('data-context-parent'=>'csfSentToRRL','data-context-value'=> TripleChoice::YES)))
                                 ->add('csfRRLDateReceived',        'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.csfRRLDateReceived',     'attr'=>array('data-context-parent'=>'csfSentToRRL','data-context-value'=> TripleChoice::YES)))
                                 ->add('csfRRLDNAExtractionDate',   'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.csfRRLDNAExtractionDate','attr'=>array('data-context-parent'=>'csfSentToRRL','data-context-value'=> TripleChoice::YES)))
                                 ->add('csfRRLDNAVolume',           null,                   array('required'=>false, 'label'=>'meningitis-form.csfRRLDNAVolume',        'attr'=>array('data-context-parent'=>'csfSentToRRL','data-context-value'=> TripleChoice::YES)))

                                 ->add('csfIsolateSentToRRL',       'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csfIsolateSentToRRL',       'attr'=>array('data-context-child' =>'csfIsolateSentToRRL')))
                                 ->add('csfIsolateDateSentToRRL',   'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.csfIsolateDateSentToRRL',   'attr'=>array('data-context-parent'=>'csfIsolateSentToRRL','data-context-value'=> TripleChoice::YES)))
                                 ->add('csfIsolateRRLDateReceived', 'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.csfIsolateRRLDateReceived', 'attr'=>array('data-context-parent'=>'csfIsolateSentToRRL','data-context-value'=> TripleChoice::YES)))

                                 ->add('bloodSentToRRL',            'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.bloodSentToRRL',         'attr'=>array('data-context-child' =>'bloodSentToRRL')))
                                 ->add('bloodDateSentToRRL',        'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.bloodDateSentToRRL',     'attr'=>array('data-context-parent'=>'bloodSentToRRL','data-context-value'=> TripleChoice::YES)))
                                 ->add('bloodRRLId',                null,                   array('required'=>false, 'label'=>'meningitis-form.bloodRRLId',             'attr'=>array('data-context-parent'=>'bloodSentToRRL','data-context-value'=> TripleChoice::YES)))
                                 ->add('bloodRRLDateReceived',      'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.bloodRRLDateReceived',   'attr'=>array('data-context-parent'=>'bloodSentToRRL','data-context-value'=> TripleChoice::YES)))

                                 ->add('bloodIsolateSentToRRL',      'TripleChoice',        array('required'=>false, 'label'=>'meningitis-form.bloodIsolateSentToRRL',       'attr'=>array('data-context-child' =>'bloodIsolateSentToRRL')))
                                 ->add('bloodIsolateDateSentToRRL',  'acedatepicker',       array('required'=>false, 'label'=>'meningitis-form.bloodIsolateDateSentToRRL',   'attr'=>array('data-context-parent'=>'bloodIsolateSentToRRL','data-context-value'=> TripleChoice::YES)))
                                 ->add('bloodIsolateRRLDateReceived','acedatepicker',       array('required'=>false, 'label'=>'meningitis-form.bloodIsolateRRLDateReceived', 'attr'=>array('data-context-parent'=>'bloodIsolateSentToRRL','data-context-value'=> TripleChoice::YES)))

                                 ->add('otherSentToRRL',            'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.otherSentToRRL',         'attr'=>array('data-context-child' =>'otherSentToRRL')))
                                 ->add('otherRRLId',                null,                   array('required'=>false, 'label'=>'meningitis-form.otherRRLId',             'attr'=>array('data-context-parent'=>'otherSentToRRL','data-context-value'=> TripleChoice::YES)))
                                 ->add('otherDateSentToRRL',        'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.otherDateSentToRRL',     'attr'=>array('data-context-parent'=>'otherSentToRRL','data-context-value'=> TripleChoice::YES)))
                                 ->add('otherRRLDateReceived',      'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.otherRRLDateReceived',   'attr'=>array('data-context-parent'=>'otherSentToRRL','data-context-value'=> TripleChoice::YES)))
                                    ;
                        }

                        if($country->hasNationalLab())
                        {
                            $form->add('csfSentToNL',               'TripleChoice',        array('required'=>false, 'label'=>'meningitis-form.csfSentToNL',            'attr'=>array('data-context-child' =>'csfSentToNL')))
                                 ->add('csfDateSentToNL',           'acedatepicker',       array('required'=>false, 'label'=>'meningitis-form.csfDateSentToNL',        'attr'=>array('data-context-parent'=>'csfSentToNL','data-context-value'=> TripleChoice::YES)))
                                 ->add('csfNLId',                   null,                  array('required'=>false, 'label'=>'meningitis-form.csfNLId',                'attr'=>array('data-context-parent'=>'csfSentToNL','data-context-value'=> TripleChoice::YES)))
                                 ->add('csfNLDateReceived',         'acedatepicker',       array('required'=>false, 'label'=>'meningitis-form.csfNLDateReceived',      'attr'=>array('data-context-parent'=>'csfSentToNL','data-context-value'=> TripleChoice::YES)))
                                 ->add('csfNLDNAExtractionDate',    'acedatepicker',       array('required'=>false, 'label'=>'meningitis-form.csfNLDNAExtractionDate', 'attr'=>array('data-context-parent'=>'csfSentToNL','data-context-value'=> TripleChoice::YES)))
                                 ->add('csfNLDNAVolume',            null,                  array('required'=>false, 'label'=>'meningitis-form.csfNLDNAVolume',         'attr'=>array('data-context-parent'=>'csfSentToNL','data-context-value'=> TripleChoice::YES)))

                                 ->add('csfIsolateSentToNL',       'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.csfIsolateSentToNL',     'attr'=>array('data-context-child' =>'csfIsolateSentToNL')))
                                 ->add('csfIsolateDateSentToNL',   'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.csfIsolateDateSentToNL', 'attr'=>array('data-context-parent'=>'csfIsolateSentToNL','data-context-value'=> TripleChoice::YES)))
                                 ->add('csfIsolateNLDateReceived', 'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.csfIsolateNLDateReceived',       'attr'=>array('data-context-parent'=>'csfIsolateSentToNL','data-context-value'=> TripleChoice::YES)))

                                 ->add('bloodSentToNL',            'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.bloodSentToNL',           'attr'=>array('data-context-child' =>'bloodSentToNL')))
                                 ->add('bloodDateSentToNL',        'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.bloodDateSentToNL',       'attr'=>array('data-context-parent'=>'bloodSentToNL','data-context-value'=> TripleChoice::YES)))
                                 ->add('bloodNLId',                null,                   array('required'=>false, 'label'=>'meningitis-form.bloodNLId',               'attr'=>array('data-context-parent'=>'bloodSentToNL','data-context-value'=> TripleChoice::YES)))
                                 ->add('bloodNLDateReceived',      'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.bloodNLDateReceived',     'attr'=>array('data-context-parent'=>'bloodSentToNL','data-context-value'=> TripleChoice::YES)))

                                 ->add('bloodIsolateSentToNL',      'TripleChoice',        array('required'=>false, 'label'=>'meningitis-form.bloodIsolateSentToNL',         'attr'=>array('data-context-child' =>'bloodIsolateSentToNL')))
                                 ->add('bloodIsolateDateSentToNL',  'acedatepicker',       array('required'=>false, 'label'=>'meningitis-form.bloodIsolateDateSentToNL',     'attr'=>array('data-context-parent'=>'bloodIsolateSentToNL','data-context-value'=> TripleChoice::YES)))
                                 ->add('bloodIsolateNLDateReceived','acedatepicker',       array('required'=>false, 'label'=>'meningitis-form.bloodIsolateNLDateReceived',   'attr'=>array('data-context-parent'=>'bloodIsolateSentToNL','data-context-value'=> TripleChoice::YES)))

                                 ->add('otherSentToNL',            'TripleChoice',         array('required'=>false, 'label'=>'meningitis-form.otherSentToNL',           'attr'=>array('data-context-child' =>'otherSentToNL')))
                                 ->add('otherNLId',                null,                   array('required'=>false, 'label'=>'meningitis-form.otherNLId',               'attr'=>array('data-context-parent'=>'otherSentToNL','data-context-value'=> TripleChoice::YES)))
                                 ->add('otherDateSentToNL',        'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.otherDateSentToNL',       'attr'=>array('data-context-parent'=>'otherSentToNL','data-context-value'=> TripleChoice::YES)))
                                 ->add('otherNLDateReceived',      'acedatepicker',        array('required'=>false, 'label'=>'meningitis-form.otherNLDateReceived',     'attr'=>array('data-context-parent'=>'otherSentToNL','data-context-value'=> TripleChoice::YES)))
                                ;
                        }
                    }
                });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\IBD'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ibd_lab';
    }
}
