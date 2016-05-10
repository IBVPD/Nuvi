<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\Form\FormEvent;
use \NS\SentinelBundle\Services\SerializedSites;
use \NS\SentinelBundle\Entity\Country;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
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
            ->add('received',           'NS\AceBundle\Form\DatePickerType')
            ->add('adequate',           'NS\SentinelBundle\Form\Types\TripleChoice')
            ->add('stored',             'NS\SentinelBundle\Form\Types\TripleChoice')
            ->add('elisaDone',          'NS\SentinelBundle\Form\Types\TripleChoice', array('attr' => $attr1))
            ->add('elisaKit',           'NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit', array('attr' => array('data-context-parent' => 'stool1Reminder', 'data-context-child' => 'elisaKit', 'data-context-value' => TripleChoice::YES)))
            ->add('elisaKitOther',      null, array('attr' => array('data-context-parent' => 'elisaKit', 'data-context-value' => ElisaKit::OTHER)))
            ->add('elisaLoadNumber',    null, array('attr' => $attr1p1))
            ->add('elisaExpiryDate',    'NS\AceBundle\Form\DatePickerType', array('attr' => $attr1p1))
            ->add('elisaTestDate',      'NS\AceBundle\Form\DatePickerType', array('attr' => $attr1p1))
            ->add('elisaResult',        'NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult', array('attr' => $attr1p1))
            ->add('genotypingDate',     'NS\AceBundle\Form\DatePickerType')
            ->add('genotypingResultG',  'NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG')
            ->add('genotypingResultGSpecify')
            ->add('genotypeResultP',    'NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP')
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
        } elseif (!$this->siteSerializer->hasMultipleSites()) {
            $site = $this->siteSerializer->getSite();
            $country = ($site instanceof Site) ? $site->getCountry() : null;
        }

        if ($country instanceof Country) {
            if ($country->hasReferenceLab()) {
                $form
                    ->add('stoolSentToRRL', 'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'rotavirus-form.stoolSentToRRL', 'attr' => array('data-context-child' => 'stoolSentToRRL')))
                    ->add('stoolSentToRRLDate', 'NS\AceBundle\Form\DatePickerType', array('required' => false, 'label' => 'rotavirus-form.stoolSentToRRLDate', 'attr' => array('data-context-parent' => 'stoolSentToRRL', 'data-context-value' => TripleChoice::YES)));
            }

            if ($country->hasNationalLab()) {
                $form
                    ->add('stoolSentToNL', 'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'rotavirus-form.stoolSentToNL', 'attr' => array('data-context-child' => 'stoolSentToNL')))
                    ->add('stoolSentToNLDate', 'NS\AceBundle\Form\DatePickerType', array('required' => false, 'label' => 'rotavirus-form.stoolSentToNLDate', 'attr' => array('data-context-parent' => 'stoolSentToNL', 'data-context-value' => TripleChoice::YES)));
            }
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\RotaVirus\SiteLab'
        ));
    }
}
