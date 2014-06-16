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
                            $form->add('stoolSentToRRL',     'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolSentToRRL', 'attr'=>array('data-context-child'=>'stoolSentToRRL')))
                                 ->add('stoolSentToRRLDate', 'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolSentToRRLDate', 'attr'=>array('data-context-parent'=>'stoolSentToRRL','data-context-value'=>  TripleChoice::YES)));
                        }

                        if($country->hasNationalLab())
                        {
                            $form->add('stoolSentToNL',     'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolSentToNL', 'attr'=>array('data-context-child'=>'stoolSentToNL')))
                                 ->add('stoolSentToNLDate', 'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolSentToNLDate', 'attr'=>array('data-context-parent'=>'stoolSentToNL','data-context-value'=>  TripleChoice::YES)));
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
