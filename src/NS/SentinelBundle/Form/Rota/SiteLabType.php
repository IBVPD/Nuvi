<?php

namespace NS\SentinelBundle\Form\Rota;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\Form\FormEvent;
use \NS\SentinelBundle\Services\SerializedSites;
use \NS\SentinelBundle\Entity\Country;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\ElisaKit;

class SiteLabType extends AbstractType
{
    private $siteSerializer;

    /**
     * @param SerializedSites $siteSerializer
     */
    public function __construct(SerializedSites $siteSerializer)
    {
        $this->siteSerializer = $siteSerializer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attr1 = array(
            'data-context-parent' => 'stool',
            'data-context-child'  => 'stool1Reminder',
            'data-context-value'  => TripleChoice::YES);

        $attr2 = array(
            'data-context-parent' => 'stool',
            'data-context-child'  => 'stool2Reminder',
            'data-context-value'  => TripleChoice::YES);

        $attr1p1 = array(
            'data-context-parent' => 'stool1Reminder',
            'data-context-value'  => TripleChoice::YES);

        $attr2p1 = array(
            'data-context-parent' => 'stool2Reminder',
            'data-context-value'  => TripleChoice::YES);

        $builder
            ->add('received', 'acedatepicker')
            ->add('adequate', 'TripleChoice', array('attr' => array('data-context-child' => 'stool')))
            ->add('stored', 'TripleChoice')
            ->add('elisaDone', 'TripleChoice', array('attr' => $attr1))
            ->add('elisaKit', 'ElisaKit', array('attr' => array(
                    'data-context-parent' => 'stool1Reminder',
                    'data-context-child'  => 'elisaKit',
                    'data-context-value'  => TripleChoice::YES)))
            ->add('elisaKitOther', null, array('attr' => array(
                    'data-context-parent' => 'elisaKit',
                    'data-context-value'  => ElisaKit::OTHER)))
            ->add('elisaLoadNumber', null, array('attr' => $attr1p1))
            ->add('elisaExpiryDate', 'acedatepicker', array('attr' => $attr1p1))
            ->add('elisaTestDate', 'acedatepicker', array('attr' => $attr1p1))
            ->add('elisaResult', 'ElisaResult', array('attr' => $attr1p1))
            ->add('secondaryElisaDone', 'TripleChoice', array('attr' => $attr2))
            ->add('secondaryElisaKit', 'ElisaKit', array('attr' => array(
                    'data-context-parent' => 'stool2Reminder',
                    'data-context-child'  => 'elisaKit2',
                    'data-context-value'  => TripleChoice::YES)))
            ->add('secondaryElisaKitOther', null, array('attr' => array(
                    'data-context-parent' => 'elisaKit2',
                    'data-context-value'  => ElisaKit::OTHER)))
            ->add('secondaryElisaLoadNumber', null, array('attr' => $attr2p1))
            ->add('secondaryElisaExpiryDate', 'acedatepicker', array('attr' => $attr2p1))
            ->add('secondaryElisaTestDate', 'acedatepicker', array('attr' => $attr2p1))
            ->add('secondaryElisaResult', 'ElisaResult', array('attr' => $attr2p1))
            ->add('genotypingDate', 'acedatepicker')
            ->add('genotypingResultG', 'GenotypeResultG')
            ->add('genotypingResultGSpecify')
            ->add('genotypeResultP', 'GenotypeResultP')
            ->add('genotypeResultPSpecify')
        ;
        $siteSerializer = $this->siteSerializer;
        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use($siteSerializer) {
            $data    = $event->getData();
            $form    = $event->getForm();
            $country = null;

            if ($data && $data->getCase() && $data->getCase()->getCountry())
                $country = $data->getCase()->getCountry();
            else if (!$siteSerializer->hasMultipleSites())
            {
                $site    = $siteSerializer->getSite();
                $country = ($site instanceof \NS\SentinelBundle\Entity\Site) ? $site->getCountry() : null;
            }

            if ($country instanceof Country)
            {
                if ($country->hasReferenceLab())
                {
                    $form->add('stoolSentToRRL', 'TripleChoice', array(
                            'required' => false,
                            'label'    => 'rotavirus-form.stoolSentToRRL',
                            'attr'     => array('data-context-child' => 'stoolSentToRRL')
                        ))
                        ->add('stoolSentToRRLDate', 'acedatepicker', array(
                            'required' => false,
                            'label'    => 'rotavirus-form.stoolSentToRRLDate',
                            'attr'     => array('data-context-parent' => 'stoolSentToRRL',
                                'data-context-value' => TripleChoice::YES)
                        ));
                }

                if ($country->hasNationalLab())
                {
                    $form->add('stoolSentToNL', 'TripleChoice', array(
                            'required' => false,
                            'label'    => 'rotavirus-form.stoolSentToNL',
                            'attr'     => array('data-context-child' => 'stoolSentToNL')
                        ))
                        ->add('stoolSentToNLDate', 'acedatepicker', array(
                            'required' => false,
                            'label'    => 'rotavirus-form.stoolSentToNLDate',
                            'attr'     => array('data-context-parent' => 'stoolSentToNL',
                                'data-context-value' => TripleChoice::YES)
                        ));
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
        return 'rotavirus_lab';
    }

}
