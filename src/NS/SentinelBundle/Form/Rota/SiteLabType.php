<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use NS\SentinelBundle\Services\SerializedSites;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Form\Types\TripleChoice;

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
            ->add('stoolReceivedDate',  'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolReceivedDate'))
            ->add('stoolAdequate',      'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolAdequate',  'attr'=>array('data-context-child'=>'stool'))) //If Yes show then show test done
            ->add('stoolELISADone',     'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolELISADone', 'attr'=>array('data-context-parent'=>'stool','data-context-child'=>'stoolReminder','data-context-value'=>TripleChoice::YES))) // If Yes show stoolTestDate, stoolELISAResult
            ->add('stoolTestDate',      'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolTestDate',   'attr'=>array('data-context-parent'=>'stoolReminder','data-context-value'=>TripleChoice::YES)))
            ->add('stoolELISAResult',   'ElisaResult',      array('required'=>false, 'label'=>'rotavirus-form.stoolELISAResult','attr'=>array('data-context-parent'=>'stoolReminder','data-context-value'=>TripleChoice::YES)))
            ->add('stoolStored',        'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolStored'))
            ->add('stoolSentToRRL',     'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolSentToRRL'))
            ->add('stoolSentToRRLDate', 'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolSentToRRLDate'))
            ->add('rrlELISAResult',     'ElisaResult',      array('required'=>false, 'label'=>'rotavirus-form.rrlELISAResult'))
            ->add('rrlGenoTypeDate',    'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.rrlGenoTypeDate'))
            ->add('rrlGenoTypeResult',  null,               array('required'=>false, 'label'=>'rotavirus-form.rrlGenoTypeResult'))
            ->add('stoolSentToNL',      'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolSentToNL'))
            ->add('stoolSentToNLDate',  'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolSentToNLDate'))
            ->add('nlELISAResult',      'ElisaResult',      array('required'=>false, 'label'=>'rotavirus-form.nlELISAResult'))
            ->add('nlGenoTypeDate',     'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.nlGenoTypeDate'))
            ->add('nlGenoTypeResult',   null,               array('required'=>false, 'label'=>'rotavirus-form.nlGenoTypeResult'))
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
            'data_class' => 'NS\SentinelBundle\Entity\Rota\SiteLab'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rotavirus_sitelab';
    }
}
