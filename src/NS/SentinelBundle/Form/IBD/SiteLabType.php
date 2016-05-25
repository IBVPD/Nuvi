<?php

namespace NS\SentinelBundle\Form\IBD;

use NS\AceBundle\Form\DatePickerType;
use NS\AceBundle\Form\SwitchType;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\Form\FormEvent;
use \NS\SentinelBundle\Services\SerializedSites;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\IBD\Types\CultureResult;
use \NS\SentinelBundle\Form\IBD\Types\PCRResult;
use \NS\SentinelBundle\Form\IBD\Types\GramStain;
use \NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use \NS\SentinelBundle\Entity\Country;
use \NS\SentinelBundle\Entity\Site;

/**
 * Class SiteLabType
 * @package NS\SentinelBundle\Form\IBD
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
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csfLabDate',         'NS\AceBundle\Form\DatePickerType', array('required' => false, 'label' => 'ibd-form.csf-lab-datetime'))
            ->add('csfLabTime',         'NS\UtilBundle\Form\Types\TimeType', array('required' => false, 'label' => 'ibd-form.csf-lab-datetime', 'widget' => 'single_text',))
            ->add('csfId',              null, array('required' => false, 'label' => 'ibd-form.csf-id'))
            ->add('csfWcc',             null, array('required' => false, 'label' => 'ibd-form.csf-wcc'))
            ->add('csfGlucose',         null, array('required' => false, 'label' => 'ibd-form.csf-glucose'))
            ->add('csfProtein',         null, array('required' => false, 'label' => 'ibd-form.csf-protein'))
            ->add('csfCultDone',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-cult-done','attr' => array('data-context-child' => 'csfCultDone')))
            ->add('csfCultResult',      'NS\SentinelBundle\Form\IBD\Types\CultureResult', array('required' => false, 'label' => 'ibd-form.csf-cult-result','attr' => array('data-context-parent' => 'csfCultDone', 'data-context-child' => 'csfCultDoneOther', 'data-context-value' => TripleChoice::YES)))
            ->add('csfCultOther',       null, array('required' => false, 'label' => 'ibd-form.csf-culture-other','attr' => array('data-context-parent' => 'csfCultDoneOther', 'data-context-value' => CultureResult::OTHER)))
            ->add('csfCultContaminant', null, array('required' => false, 'label' => 'ibd-form.csf-culture-contaminant', 'attr' => array('data-context-parent' => 'csfCultDoneOther', 'data-context-value' => CultureResult::CONTAMINANT)))
            ->add('csfGramDone',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-gram-done', 'attr' => array('data-context-child' => 'csfGramDone')))
            ->add('csfGramStain',       'NS\SentinelBundle\Form\IBD\Types\GramStain', array('required' => false, 'label' => 'ibd-form.csf-gram-result', 'attr' => array('data-context-parent' => 'csfGramDone', 'data-context-child' => 'csfGramStain', 'data-context-value' => TripleChoice::YES)))
            ->add('csfGramResult',      'NS\SentinelBundle\Form\IBD\Types\GramStainResult', array('required' => false, 'label' => 'ibd-form.csf-gram-result-organism', 'attr' => array('data-context-parent' => 'csfGramStain', 'data-context-child' => 'csfGramResult', 'data-context-value' => json_encode(array(GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE)))))
            ->add('csfGramOther',       null, array('required' => false, 'label' => 'ibd-form.csf-gram-other', 'attr' => array('data-context-parent' => 'csfGramResult', 'data-context-value' => GramStainResult::OTHER)))
            ->add('csfBinaxDone',       'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-binax-done', 'attr' => array('data-context-child' => 'csfBinaxDone')))
            ->add('csfBinaxResult',     'NS\SentinelBundle\Form\IBD\Types\BinaxResult', array('required' => false, 'label' => 'ibd-form.csf-binax-result', 'attr' => array('data-context-parent' => 'csfBinaxDone', 'data-context-value' => TripleChoice::YES)))
            ->add('csfLatDone',         'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-lat-done', 'attr' => array('data-context-child' => 'csfLatDone')))
            ->add('csfLatResult',       'NS\SentinelBundle\Form\IBD\Types\LatResult', array('required' => false, 'label' => 'ibd-form.csf-lat-result', 'attr' => array('data-context-parent' => 'csfLatDone', 'data-context-value' => TripleChoice::YES)))
            ->add('csfLatOther',        null, array('required' => false, 'label' => 'ibd-form.csf-lat-other', 'attr' => array('data-context-parent' => 'csfLatDone', 'data-context-value' => TripleChoice::YES)))
            ->add('csfPcrDone',         'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-pcr-done', 'attr' => array('data-context-child' => 'csfPcrDone')))
            ->add('csfPcrResult',       'NS\SentinelBundle\Form\IBD\Types\PCRResult', array('required' => false, 'label' => 'ibd-form.csf-pcr-result', 'attr' => array('data-context-parent' => 'csfPcrDone', 'data-context-child' => 'csfPcrDoneResult', 'data-context-value' => TripleChoice::YES)))
            ->add('csfPcrOther',        null, array('required' => false, 'label' => 'ibd-form.csf-pcr-other', 'attr' => array('data-context-parent' => 'csfPcrDoneResult', 'data-context-value' => PCRResult::OTHER)))
            ->add('csfStore',           'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-store'))
            ->add('isolStore',          'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.isol-store'))
            ->add('bloodId',            null, array('required' => false, 'label' => 'ibd-form.blood-id'))
            ->add('bloodCultDone',      'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.blood-cult-done', 'attr' => array('data-context-child' => 'bloodCultDone')))
            ->add('bloodCultResult',    'NS\SentinelBundle\Form\IBD\Types\CultureResult', array('required' => false, 'label' => 'ibd-form.blood-cult-result', 'attr' => array('data-context-parent' => 'bloodCultDone', 'data-context-value' => TripleChoice::YES)))
            ->add('bloodCultOther',     null, array('required' => false, 'label' => 'ibd-form.blood-cult-other', 'attr' => array('data-context-parent' => 'bloodCultDone', 'data-context-value' => TripleChoice::YES)))
            ->add('bloodGramDone',      'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.blood-gram-done', 'attr' => array('data-context-child' => 'bloodGramDone')))
            ->add('bloodGramStain',     'NS\SentinelBundle\Form\IBD\Types\GramStain', array('required' => false, 'label' => 'ibd-form.blood-gram-result', 'attr' => array('data-context-parent' => 'bloodGramDone', 'data-context-child' => 'bloodGramStain', 'data-context-value' => TripleChoice::YES)))
            ->add('bloodGramResult',    'NS\SentinelBundle\Form\IBD\Types\GramStainResult', array('required' => false, 'label' => 'ibd-form.blood-gram-result-organism', 'attr' => array('data-context-parent' => 'bloodGramStain', 'data-context-child' => 'bloodGramResultOther', 'data-context-value' => json_encode(array(GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE)))))
            ->add('bloodGramOther',     null, array('required' => false, 'label' => 'ibd-form.blood-gram-other', 'attr' => array('data-context-parent' => 'bloodGramResultOther', 'data-context-child' => '', 'data-context-value' => GramStainResult::OTHER)))
            ->add('bloodPcrDone',       'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.blood-pcr-done', 'attr' => array('data-context-child' => 'bloodPcrDone')))
            ->add('bloodPcrResult',     'NS\SentinelBundle\Form\IBD\Types\PCRResult', array('required' => false, 'label' => 'ibd-form.blood-pcr-result', 'attr' => array('data-context-parent' => 'bloodPcrDone', 'data-context-value' => TripleChoice::YES)))
            ->add('bloodPcrOther',      null, array('required' => false, 'label' => 'ibd-form.blood-pcr-other', 'attr' => array('data-context-parent' => 'bloodPcrDone', 'data-context-value' => TripleChoice::YES)))
            ->add('otherCultDone',      'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.other-cult-done1', 'attr' => array('data-context-child' => 'otherCultDone')))
            ->add('otherCultResult',    'NS\SentinelBundle\Form\IBD\Types\CultureResult', array('required' => false, 'label' => 'ibd-form.other-cult-result', 'attr' => array('data-context-parent' => 'otherCultDone', 'data-context-child' => 'otherCultResult', 'data-context-value' => TripleChoice::YES)))
            ->add('otherCultOther',     null, array('required' => false, 'label' => 'ibd-form.other-cult-other', 'attr' => array('data-context-parent' => 'otherCultResult', 'data-context-value' => CultureResult::OTHER)))
            ->add('otherTestDone',      'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.other-test-done1', 'attr' => array('data-context-child' => 'otherTestDone')))
            ->add('otherTestResult',    'NS\SentinelBundle\Form\IBD\Types\CultureResult', array('required' => false, 'label' => 'ibd-form.other-test-result', 'attr' => array('data-context-parent' => 'otherTestDone', 'data-context-child'=>'otherTestResult', 'data-context-value' => TripleChoice::YES)))
            ->add('otherTestOther',     null, array('required' => false, 'label' => 'ibd-form.other-test-other', 'attr' => array('data-context-parent' => 'otherTestResult', 'data-context-value' => CultureResult::OTHER)))
        ;

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
                    ->add('sentToReferenceLab', SwitchType::class, array('label'=>'Sent To Reference Lab','required' => false, 'attr'=>array('data-context-child'=>'sentToReferenceLab')))
                    ->add('csfSentToRRLDate', DatePickerType::class, array('label'=>'ibd-form.csf-sent-to-rrl-date', 'required'=>false, 'attr'=>array('data-context-parent'=>'sentToReferenceLab', 'data-context-value'=>1)))
                    ->add('csfIsolSentToRRLDate', DatePickerType::class, array('label'=>'ibd-form.csf-isol-sent-to-rrl-date', 'required'=>false, 'attr'=>array('data-context-parent'=>'sentToReferenceLab', 'data-context-value'=>1)))
                    ->add('bloodIsolSentToRRLDate', DatePickerType::class, array('label'=>'ibd-form.blood-sent-to-rrl-date', 'required'=>false, 'attr'=>array('data-context-parent'=>'sentToReferenceLab', 'data-context-value'=>1)))
                    ->add('brothSentToRRLDate', DatePickerType::class, array('label'=>'ibd-form.broth-sent-to-rrl-date', 'required'=>false, 'attr'=>array('data-context-parent'=>'sentToReferenceLab', 'data-context-value'=>1)))
                ;
            }

            if ($country->hasNationalLab()) {
                $form->add('sentToNationalLab', SwitchType::class, array('required' => false,'label'=>'Sent To National Lab',));
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\IBD\SiteLab'
        ));
    }
}
