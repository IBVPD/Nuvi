<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use NS\SentinelBundle\Form\Types\GenotypeResultG;
use NS\SentinelBundle\Form\Types\GenotypeResultP;
use NS\SentinelBundle\Services\SerializedSites;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\ElisaKit;
use NS\SentinelBundle\Entity\Country;

class LabType extends AbstractType
{
    private $siteSerializer;

    public function __construct(SerializedSites $serializer)
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
            ->add('siteReceivedDate',       'acedatepicker',    array('required'=>false,'label'=>'external-lab-form.siteReceivedDate'))
            ->add('siteLabId',              null,               array('required'=>false,'label'=>'external-lab-form.siteLabId'))
            ->add('adequate',               'TripleChoice',     array('required'=>false,'label'=>'external-lab-form.adequate'))
            ->add('stored',                 'TripleChoice',     array('required'=>false,'label'=>'external-lab-form.stored'))
            ->add('elisaDone',              'TripleChoice',     array('required'=>false,'label'=>'external-lab-form.elisaDone','attr' => array('data-context-child'  => 'elisaDone')))
            ->add('elisaKit',               'ElisaKit',         array('required'=>false,'label'=>'external-lab-form.elisaKit','attr' => array('data-context-parent'  => 'elisaDone', 'data-context-child'  => 'elisaKitOther', 'data-context-value'=> TripleChoice::YES)))
            ->add('elisaKitOther',          null,               array('required'=>false,'label'=>'external-lab-form.elisaKitOther','attr' => array('data-context-parent'  => 'elisaKitOther', 'data-context-value'=> ElisaKit::OTHER)))
            ->add('elisaLoadNumber',        null,               array('required'=>false,'label'=>'external-lab-form.elisaLoadNumber','attr' => array('data-context-parent'  => 'elisaDone', 'data-context-value'=> TripleChoice::YES)))
            ->add('elisaExpiryDate',        'acedatepicker',    array('required'=>false,'label'=>'external-lab-form.elisaExpiryDate','attr' => array('data-context-parent'  => 'elisaDone', 'data-context-value'=> TripleChoice::YES)))
            ->add('elisaTestDate',          'acedatepicker',    array('required'=>false,'label'=>'external-lab-form.elisaTestDate','attr' => array('data-context-parent'  => 'elisaDone', 'data-context-value'=> TripleChoice::YES)))
            ->add('elisaResult',            'ElisaResult',      array('required'=>false,'label'=>'external-lab-form.elisaResult','attr' => array('data-context-parent'  => 'elisaDone', 'data-context-value'=> TripleChoice::YES)))
            ->add('secondaryElisaDone',     'TripleChoice',     array('required'=>false,'label'=>'external-lab-form.secondaryElisaDone','attr' => array('data-context-child'  => 'secondaryElisaDone')))
            ->add('secondaryElisaKit',      'ElisaKit',         array('required'=>false,'label'=>'external-lab-form.secondaryElisaKit','attr' => array('data-context-parent'  => 'secondaryElisaDone', 'data-context-child'  => 'secondaryElisaKitOther', 'data-context-value'=> TripleChoice::YES)))
            ->add('secondaryElisaKitOther',  null,              array('required'=>false,'label'=>'external-lab-form.secondaryElisaKitOther','attr' => array('data-context-parent'  => 'secondaryElisaKitOther', 'data-context-value'=> ElisaKit::OTHER)))
            ->add('secondaryElisaLoadNumber',null,              array('required'=>false,'label'=>'external-lab-form.secondaryElisaLoadNumber','attr' => array('data-context-parent'  => 'secondaryElisaDone', 'data-context-value'=> TripleChoice::YES)))
            ->add('secondaryElisaExpiryDate','acedatepicker',   array('required'=>false,'label'=>'external-lab-form.secondaryElisaExpiryDate','attr' => array('data-context-parent'  => 'secondaryElisaDone', 'data-context-value'=> TripleChoice::YES)))
            ->add('secondaryElisaTestDate', 'acedatepicker',    array('required'=>false,'label'=>'external-lab-form.secondaryElisaTestDate','attr' => array('data-context-parent'  => 'secondaryElisaDone', 'data-context-value'=> TripleChoice::YES)))
            ->add('secondaryElisaResult',   'ElisaResult',      array('required'=>false,'label'=>'external-lab-form.secondaryElisaResult','attr' => array('data-context-parent'  => 'secondaryElisaDone', 'data-context-value'=> TripleChoice::YES)))
            ->add('genotypingDate',         'acedatepicker',    array('required'=>false,'label'=>'external-lab-form.genotyping-date'))
            ->add('genotypingResultG',      'GenotypeResultG',  array('required'=>false,'label'=>'external-lab-form.genotyping-result-g',         'attr' => array('data-context-child'  => 'genotypingResultG')))
            ->add('genotypingResultGSpecify',null,              array('required'=>false,'label'=>'external-lab-form.genotyping-result-g-specify', 'attr' => array('data-context-parent' => 'genotypingResultG', 'data-context-value' => json_encode(array(GenotypeResultG::OTHER,GenotypeResultG::MIXED)))))
            ->add('genotypeResultP',        'GenotypeResultP',  array('required'=>false,'label'=>'external-lab-form.genotyping-result-p',         'attr' => array('data-context-child'  => 'genotypingResultP')))
            ->add('genotypeResultPSpecify', null,               array('required'=>false,'label'=>'external-lab-form.genotyping-result-p-specify', 'attr' => array('data-context-parent' => 'genotypingResultP', 'data-context-value' => json_encode(array(GenotypeResultP::OTHER,GenotypeResultP::MIXED)))))
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
                        {
                            $form->add('sentToRRL',       'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.sentToRRL',       'attr'=>array('data-context-child'=>'stoolSentToRRL')))
                                 ->add('sentToRRLDate',   'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.sentToRRLDate',   'attr'=>array('data-context-parent'=>'stoolSentToRRL','data-context-value'=>  TripleChoice::YES)))
                                 ->add('rrlReceivedDate', 'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.rrlReceivedDate', 'attr'=>array('data-context-parent'=>'stoolSentToRRL','data-context-value'=>  TripleChoice::YES)))
                                 ->add('rrlLabId',        null,               array('required'=>false, 'label'=>'rotavirus-form.rrlLabId',        'attr'=>array('data-context-parent'=>'stoolSentToRRL','data-context-value'=>  TripleChoice::YES)));
                        }

                        if($country->hasNationalLab())
                        {
                            $form->add('sentToNL',       'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.sentToNL',       'attr'=>array('data-context-child'=>'stoolSentToNL')))
                                 ->add('sentToNLDate',   'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.sentToNLDate',   'attr'=>array('data-context-parent'=>'stoolSentToNL','data-context-value'=>  TripleChoice::YES)))
                                 ->add('nlReceivedDate', 'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.nlReceivedDate', 'attr'=>array('data-context-parent'=>'stoolSentToNL','data-context-value'=>  TripleChoice::YES)))
                                 ->add('nlLabId',        null,               array('required'=>false, 'label'=>'rotavirus-form.nlLabId',        'attr'=>array('data-context-parent'=>'stoolSentToNL','data-context-value'=>  TripleChoice::YES)));
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
            'data_class' => 'NS\SentinelBundle\Entity\Rota\Lab'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rotavirus_lab';
    }
}
