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
            ->add('csfLabDate',         'NS\AceBundle\Form\DatePickerType', array('required' => false, 'label' => 'ibd-form.csf-lab-date'))
            ->add('csfLabTime',         'Symfony\Component\Form\Extension\Core\Type\TimeType', array('required' => false, 'label' => 'ibd-form.csf-lab-time','minutes'=>[0,5,10,15,20,25,30,35,40,45,50,55]))
            ->add('csfId',              null, array('required' => false, 'label' => 'ibd-form.csf-id'))
            ->add('csfWcc',             null, array('required' => false, 'label' => 'ibd-form.csf-wcc'))
            ->add('csfGlucose',         null, array('required' => false, 'label' => 'ibd-form.csf-glucose'))
            ->add('csfProtein',         null, array('required' => false, 'label' => 'ibd-form.csf-protein'))
            ->add('csfCultDone',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-cult-done','hidden-child' => 'csfCultDone'))
            ->add('csfCultResult',      'NS\SentinelBundle\Form\IBD\Types\CultureResult', array('required' => false, 'label' => 'ibd-form.csf-cult-result','hidden-parent' => 'csfCultDone', 'hidden-child' => 'csfCultDoneOther', 'hidden-value' => TripleChoice::YES))
            ->add('csfCultOther',       null, array('required' => false, 'label' => 'ibd-form.csf-culture-other','hidden-parent' => 'csfCultDoneOther', 'hidden-value' => CultureResult::OTHER))
            ->add('csfCultContaminant', null, array('required' => false, 'label' => 'ibd-form.csf-culture-contaminant', 'hidden-parent' => 'csfCultDoneOther', 'hidden-value' => CultureResult::CONTAMINANT))
            ->add('csfGramDone',        'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-gram-done', 'hidden-child' => 'csfGramDone'))
            ->add('csfGramStain',       'NS\SentinelBundle\Form\IBD\Types\GramStain', array('required' => false, 'label' => 'ibd-form.csf-gram-result', 'hidden-parent' => 'csfGramDone', 'hidden-child' => 'csfGramStain', 'hidden-value' => TripleChoice::YES))
            ->add('csfGramResult',      'NS\SentinelBundle\Form\IBD\Types\GramStainResult', array('required' => false, 'label' => 'ibd-form.csf-gram-result-organism', 'hidden-parent' => 'csfGramStain', 'hidden-child' => 'csfGramResult', 'hidden-value' => json_encode(array(GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE))))
            ->add('csfGramOther',       null, array('required' => false, 'label' => 'ibd-form.csf-gram-other', 'hidden-parent' => 'csfGramResult', 'hidden-value' => GramStainResult::OTHER))
            ->add('csfBinaxDone',       'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-binax-done', 'hidden-child' => 'csfBinaxDone'))
            ->add('csfBinaxResult',     'NS\SentinelBundle\Form\IBD\Types\BinaxResult', array('required' => false, 'label' => 'ibd-form.csf-binax-result', 'hidden-parent' => 'csfBinaxDone', 'hidden-value' => TripleChoice::YES))
            ->add('csfLatDone',         'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-lat-done', 'hidden-child' => 'csfLatDone'))
            ->add('csfLatResult',       'NS\SentinelBundle\Form\IBD\Types\LatResult', array('required' => false, 'label' => 'ibd-form.csf-lat-result', 'hidden-parent' => 'csfLatDone', 'hidden-value' => TripleChoice::YES))
            ->add('csfLatOther',        null, array('required' => false, 'label' => 'ibd-form.csf-lat-other', 'hidden-parent' => 'csfLatDone', 'hidden-value' => TripleChoice::YES))
            ->add('csfPcrDone',         'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-pcr-done', 'hidden-child' => 'csfPcrDone'))
            ->add('csfPcrResult',       'NS\SentinelBundle\Form\IBD\Types\PCRResult', array('required' => false, 'label' => 'ibd-form.csf-pcr-result', 'hidden-parent' => 'csfPcrDone', 'hidden-child' => 'csfPcrDoneResult', 'hidden-value' => TripleChoice::YES))
            ->add('csfPcrOther',        null, array('required' => false, 'label' => 'ibd-form.csf-pcr-other', 'hidden-parent' => 'csfPcrDoneResult', 'hidden-value' => PCRResult::OTHER))
            ->add('csfStore',           'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.csf-store'))
            ->add('isolStore',          'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.isol-store'))
            ->add('bloodId',            null, array('required' => false, 'label' => 'ibd-form.blood-id'))
            ->add('bloodCultDone',      'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.blood-cult-done', 'hidden-child' => 'bloodCultDone'))
            ->add('bloodCultResult',    'NS\SentinelBundle\Form\IBD\Types\CultureResult', array('required' => false, 'label' => 'ibd-form.blood-cult-result', 'hidden-parent' => 'bloodCultDone', 'hidden-value' => TripleChoice::YES))
            ->add('bloodCultOther',     null, array('required' => false, 'label' => 'ibd-form.blood-cult-other', 'hidden-parent' => 'bloodCultDone', 'hidden-value' => TripleChoice::YES))
            ->add('bloodGramDone',      'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.blood-gram-done', 'hidden-child' => 'bloodGramDone'))
            ->add('bloodGramStain',     'NS\SentinelBundle\Form\IBD\Types\GramStain', array('required' => false, 'label' => 'ibd-form.blood-gram-result', 'hidden-parent' => 'bloodGramDone', 'hidden-child' => 'bloodGramStain', 'hidden-value' => TripleChoice::YES))
            ->add('bloodGramResult',    'NS\SentinelBundle\Form\IBD\Types\GramStainResult', array('required' => false, 'label' => 'ibd-form.blood-gram-result-organism', 'hidden-parent' => 'bloodGramStain', 'hidden-child' => 'bloodGramResultOther', 'hidden-value' => json_encode(array(GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE))))
            ->add('bloodGramOther',     null, array('required' => false, 'label' => 'ibd-form.blood-gram-other', 'hidden-parent' => 'bloodGramResultOther', 'hidden-child' => '', 'hidden-value' => GramStainResult::OTHER))
            ->add('bloodPcrDone',       'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.blood-pcr-done', 'hidden-child' => 'bloodPcrDone'))
            ->add('bloodPcrResult',     'NS\SentinelBundle\Form\IBD\Types\PCRResult', array('required' => false, 'label' => 'ibd-form.blood-pcr-result', 'hidden-parent' => 'bloodPcrDone', 'hidden-value' => TripleChoice::YES))
            ->add('bloodPcrOther',      null, array('required' => false, 'label' => 'ibd-form.blood-pcr-other', 'hidden-parent' => 'bloodPcrDone', 'hidden-value' => TripleChoice::YES))
            ->add('otherCultDone',      'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.other-cult-done1', 'hidden-child' => 'otherCultDone'))
            ->add('otherCultResult',    'NS\SentinelBundle\Form\IBD\Types\CultureResult', array('required' => false, 'label' => 'ibd-form.other-cult-result', 'hidden-parent' => 'otherCultDone', 'hidden-child' => 'otherCultResult', 'hidden-value' => TripleChoice::YES))
            ->add('otherCultOther',     null, array('required' => false, 'label' => 'ibd-form.other-cult-other', 'hidden-parent' => 'otherCultResult', 'hidden-value' => CultureResult::OTHER))
            ->add('otherTestDone',      'NS\SentinelBundle\Form\Types\TripleChoice', array('required' => false, 'label' => 'ibd-form.other-test-done1', 'hidden-child' => 'otherTestDone'))
            ->add('otherTestResult',    'NS\SentinelBundle\Form\IBD\Types\CultureResult', array('required' => false, 'label' => 'ibd-form.other-test-result', 'hidden-parent' => 'otherTestDone', 'hidden-child'=>'otherTestResult', 'hidden-value' => TripleChoice::YES))
            ->add('otherTestOther',     null, array('required' => false, 'label' => 'ibd-form.other-test-other', 'hidden-parent' => 'otherTestResult', 'hidden-value' => CultureResult::OTHER))
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
                    ->add('sentToReferenceLab', SwitchType::class, array('label' => 'Sent To Reference Lab', 'required' => false, 'hidden-child' => 'sentToReferenceLab', 'switch_type' => 2))
                    ->add('csfSentToRRLDate', DatePickerType::class, array('label' => 'ibd-form.csf-sent-to-rrl-date', 'required' => false, 'hidden-parent' => 'sentToReferenceLab', 'hidden-value' => 1))
                    ->add('csfIsolSentToRRLDate', DatePickerType::class, array('label' => 'ibd-form.csf-isol-sent-to-rrl-date', 'required' => false, 'hidden-parent' => 'sentToReferenceLab', 'hidden-value' => 1))
                    ->add('bloodIsolSentToRRLDate', DatePickerType::class, array('label' => 'ibd-form.blood-sent-to-rrl-date', 'required' => false, 'hidden-parent' => 'sentToReferenceLab', 'hidden-value' => 1))
                    ->add('brothSentToRRLDate', DatePickerType::class, array('label' => 'ibd-form.broth-sent-to-rrl-date', 'required' => false, 'hidden-parent' => 'sentToReferenceLab', 'hidden-value' => 1));
            }

            if ($country->hasNationalLab()) {
                $form->add('sentToNationalLab', SwitchType::class, array('required' => false, 'label' => 'Sent To National Lab', 'switch_type' => 2));
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
