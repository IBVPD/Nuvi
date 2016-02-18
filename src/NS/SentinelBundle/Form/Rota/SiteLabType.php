<?php

namespace NS\SentinelBundle\Form\Rota;

use \NS\SentinelBundle\Entity\Country;
use \NS\SentinelBundle\Form\Types\ElisaKit;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Services\SerializedSites;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use NS\SentinelBundle\Entity\Site;

/**
 * Class SiteLabType
 * @package NS\SentinelBundle\Form\Rota
 */
class SiteLabType extends AbstractType
{
    /**
     * @var SerializedSites
     */
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
            'data-context-child'  => 'stool1Reminder',
            'data-context-value'  => TripleChoice::YES);

        $attr1p1 = array(
            'data-context-parent' => 'stool1Reminder',
            'data-context-value'  => TripleChoice::YES);

        $builder
            ->add('received', 'acedatepicker')
            ->add('adequate', 'TripleChoice')
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
            ->add('genotypingDate', 'acedatepicker')
            ->add('genotypingResultG', 'GenotypeResultG')
            ->add('genotypingResultGSpecify')
            ->add('genotypeResultP', 'GenotypeResultP')
            ->add('genotypeResultPSpecify');

        $builder->addEventListener(FormEvents::POST_SET_DATA, array($this, 'postSetData'));
    }

    /**
     * @param FormEvent $event
     */
    public function postSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        $country = null;

        if ($data && $data->getCaseFile() && $data->getCaseFile()->getCountry()) {
            $country = $data->getCaseFile()->getCountry();
        } else if (!$this->siteSerializer->hasMultipleSites()) {
            $site = $this->siteSerializer->getSite();
            $country = ($site instanceof Site) ? $site->getCountry() : null;
        }

        if ($country instanceof Country) {
            if ($country->hasReferenceLab()) {
                $form->add('stoolSentToRRL', 'TripleChoice', array(
                    'required' => false,
                    'label'    => 'rotavirus-form.stoolSentToRRL',
                    'attr'     => array('data-context-child' => 'stoolSentToRRL')
                    ))
                    ->add('stoolSentToRRLDate', 'acedatepicker', array(
                        'required' => false,
                        'label'    => 'rotavirus-form.stoolSentToRRLDate',
                        'attr'     => array(
                            'data-context-parent' => 'stoolSentToRRL',
                            'data-context-value' => TripleChoice::YES)
                    ));
            }

            if ($country->hasNationalLab()) {
                $form->add('stoolSentToNL', 'TripleChoice', array(
                    'required' => false,
                    'label'    => 'rotavirus-form.stoolSentToNL',
                    'attr'     => array('data-context-child' => 'stoolSentToNL')
                    ))
                    ->add('stoolSentToNLDate', 'acedatepicker', array(
                        'required' => false,
                        'label'    => 'rotavirus-form.stoolSentToNLDate',
                        'attr'     => array(
                            'data-context-parent' => 'stoolSentToNL',
                            'data-context-value' => TripleChoice::YES)
                    ));
            }
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
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
