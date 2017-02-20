<?php

namespace NS\SentinelBundle\Form\IBD;

use NS\AceBundle\Form\DatePickerType;
use NS\AceBundle\Form\SwitchType;
use NS\SentinelBundle\Entity\IBD\SiteLab;
use NS\SentinelBundle\Form\IBD\Types\BinaxResult;
use NS\SentinelBundle\Form\IBD\Types\LatResult;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use NS\SentinelBundle\Services\SerializedSites;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\IBD\Types\PCRResult;
use NS\SentinelBundle\Form\IBD\Types\GramStain;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Site;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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

    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    /**
     * @param SerializedSites $siteSerializer
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(SerializedSites $siteSerializer, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->siteSerializer = $siteSerializer;
        $this->authChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isPaho = $this->authChecker->isGranted('ROLE_AMR');

        $builder
            ->add('csfLabDate',         DatePickerType::class, ['required' => false, 'label' => 'ibd-form.csf-lab-datetime', 'property_path' => 'csf_lab_date'])
            ->add('csfLabTime',         TimeType::class, ['required' => false, 'label' => 'ibd-form.csf-lab-time','minutes'=>[0,5,10,15,20,25,30,35,40,45,50,55], 'property_path' => 'csf_lab_time'])
            ->add('csfId',              null, ['required' => false, 'label' => 'ibd-form.csf-id'])
            ->add('csfWcc',             IntegerType::class, ['required' => false, 'label' => 'ibd-form.csf-wcc', 'property_path' => 'csf_wcc', 'attr' => ['min' => 0]])
            ->add('csfGlucose',         IntegerType::class, ['required' => false, 'label' => 'ibd-form.csf-glucose', 'property_path' => 'csf_glucose', 'attr' => ['min' => 0]])
            ->add('csfProtein',         IntegerType::class, ['required' => false, 'label' => 'ibd-form.csf-protein', 'property_path' => 'csf_protein', 'attr' => ['min' => 0]])
            ->add('csfCultDone',        TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-cult-done','hidden-child' => 'csfCultDone'])
            ->add('csfCultResult',      CultureResult::class, [
                'required' => false,
                'label' => 'ibd-form.csf-cult-result',
                'exclude_choices'=> ($isPaho ? [CultureResult::UNKNOWN]:null),
                'hidden-parent' => 'csfCultDone',
                'hidden-child' => 'csfCultDoneOther',
                'hidden-value' => TripleChoice::YES
            ])
            ->add('csfCultOther',       null, ['required' => false, 'label' => 'ibd-form.csf-culture-other','hidden-parent' => 'csfCultDoneOther', 'hidden-value' => CultureResult::OTHER])
            ->add('csfCultContaminant', null, ['required' => false, 'label' => 'ibd-form.csf-culture-contaminant', 'hidden-parent' => 'csfCultDoneOther', 'hidden-value' => CultureResult::CONTAMINANT])
            ->add('csfGramDone',        TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-gram-done', 'hidden-child' => 'csfGramDone'])
            ->add('csfGramStain',       GramStain::class, ['required' => false, 'label' => 'ibd-form.csf-gram-result', 'hidden-parent' => 'csfGramDone', 'hidden-child' => 'csfGramStain', 'hidden-value' => TripleChoice::YES])
            ->add('csfGramResult',      GramStainResult::class, ['required' => false, 'label' => 'ibd-form.csf-gram-result-organism', 'hidden-parent' => 'csfGramStain', 'hidden-child' => 'csfGramResult', 'hidden-value' => json_encode([GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE])])
            ->add('csfGramOther',       null, ['required' => false, 'label' => 'ibd-form.csf-gram-other', 'hidden-parent' => 'csfGramResult', 'hidden-value' => GramStainResult::OTHER])
            ->add('csfBinaxDone',       TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-binax-done', 'hidden-child' => 'csfBinaxDone'])
            ->add('csfBinaxResult',     BinaxResult::class, ['required' => false, 'label' => 'ibd-form.csf-binax-result', 'hidden-parent' => 'csfBinaxDone', 'hidden-value' => TripleChoice::YES])
            ->add('csfLatDone',         TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-lat-done', 'hidden-child' => 'csfLatDone', 'exclude_choices'=> ($isPaho ? [TripleChoice::UNKNOWN]:null)])
            ->add('csfLatResult',       LatResult::class, ['required' => false, 'label' => 'ibd-form.csf-lat-result', 'hidden-parent' => 'csfLatDone', 'hidden-value' => TripleChoice::YES])
            ->add('csfLatOther',        null, ['required' => false, 'label' => 'ibd-form.csf-lat-other', 'hidden-parent' => 'csfLatDone', 'hidden-value' => TripleChoice::YES])
            ->add('csfPcrDone',         TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-pcr-done', 'hidden-child' => 'csfPcrDone'])
            ->add('csfPcrResult',       PCRResult::class, ['required' => false, 'label' => 'ibd-form.csf-pcr-result', 'hidden-parent' => 'csfPcrDone', 'hidden-child' => 'csfPcrDoneResult', 'hidden-value' => TripleChoice::YES])
            ->add('csfPcrOther',        null, ['required' => false, 'label' => 'ibd-form.csf-pcr-other', 'hidden-parent' => 'csfPcrDoneResult', 'hidden-value' => PCRResult::OTHER])
            ->add('csfStore',           TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-store'])
            ->add('isolStore',          TripleChoice::class, ['required' => false, 'label' => 'ibd-form.isol-store'])
            ->add('bloodId',            null, ['required' => false, 'label' => 'ibd-form.blood-id'])
            ->add('bloodCultDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-cult-done', 'hidden-child' => 'bloodCultDone','exclude_choices'=> ($isPaho ? [TripleChoice::UNKNOWN]:null)])
            ->add('bloodCultResult',    CultureResult::class, ['required' => false, 'label' => 'ibd-form.blood-cult-result', 'hidden-parent' => 'bloodCultDone', 'hidden-value' => TripleChoice::YES,'exclude_choices'=>($isPaho?[CultureResult::UNKNOWN]:null)])
            ->add('bloodCultOther',     null, ['required' => false, 'label' => 'ibd-form.blood-cult-other', 'hidden-parent' => 'bloodCultDone', 'hidden-value' => TripleChoice::YES])
            ->add('bloodGramDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-gram-done', 'hidden-child' => 'bloodGramDone'])
            ->add('bloodGramStain',     GramStain::class, ['required' => false, 'label' => 'ibd-form.blood-gram-result', 'hidden-parent' => 'bloodGramDone', 'hidden-child' => 'bloodGramStain', 'hidden-value' => TripleChoice::YES])
            ->add('bloodGramResult',    GramStainResult::class, ['required' => false, 'label' => 'ibd-form.blood-gram-result-organism', 'hidden-parent' => 'bloodGramStain', 'hidden-child' => 'bloodGramResultOther', 'hidden-value' => json_encode([GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE])])
            ->add('bloodGramOther',     null, ['required' => false, 'label' => 'ibd-form.blood-gram-other', 'hidden-parent' => 'bloodGramResultOther', 'hidden-child' => '', 'hidden-value' => GramStainResult::OTHER])
            ->add('bloodPcrDone',       TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-pcr-done', 'hidden-child' => 'bloodPcrDone'])
            ->add('bloodPcrResult',     PCRResult::class, ['required' => false, 'label' => 'ibd-form.blood-pcr-result', 'hidden-parent' => 'bloodPcrDone', 'hidden-value' => TripleChoice::YES])
            ->add('bloodPcrOther',      null, ['required' => false, 'label' => 'ibd-form.blood-pcr-other', 'hidden-parent' => 'bloodPcrDone', 'hidden-value' => TripleChoice::YES])
            ->add('otherCultDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.other-cult-done1', 'hidden-child' => 'otherCultDone'])
            ->add('otherCultResult',    CultureResult::class, ['required' => false, 'label' => 'ibd-form.other-cult-result', 'hidden-parent' => 'otherCultDone', 'hidden-child' => 'otherCultResult', 'hidden-value' => TripleChoice::YES])
            ->add('otherCultOther',     null, ['required' => false, 'label' => 'ibd-form.other-cult-other', 'hidden-parent' => 'otherCultResult', 'hidden-value' => CultureResult::OTHER])
            ->add('otherTestDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.other-test-done1', 'hidden-child' => 'otherTestDone'])
            ->add('otherTestResult',    CultureResult::class, ['required' => false, 'label' => 'ibd-form.other-test-result', 'hidden-parent' => 'otherTestDone', 'hidden-child'=>'otherTestResult', 'hidden-value' => TripleChoice::YES])
            ->add('otherTestOther',     null, ['required' => false, 'label' => 'ibd-form.other-test-other', 'hidden-parent' => 'otherTestResult', 'hidden-value' => CultureResult::OTHER])
        ;

        if ($isPaho) {
            $builder
                ->add('pleuralFluidCultureDone', TripleChoice::class, ['required' => false, 'hidden' => ['child' => 'pleuralFluidCultureDone'], 'label'=>'ibd-form.pleural-fluid-culture-done'])
                ->add('pleuralFluidCultureResult', CultureResult::class, ['required' => false, 'hidden' => ['child'=>'pleuralFluidCultureResult','parent' => 'pleuralFluidCultureDone', 'value' => TripleChoice::YES], 'label'=>'ibd-form.pleural-fluid-culture-result'])
                ->add('pleuralFluidCultureOther', null, ['required' => false, 'hidden' => ['parent' => 'pleuralFluidCultureResult', 'value' => CultureResult::OTHER], 'label'=>'ibd-form.pleural-fluid-culture-result-other'])
                ->add('pleuralFluidGramDone', TripleChoice::class, ['required' => false, 'hidden' => ['child' => 'pleuralFluidGramDone'], 'label'=>'ibd-form.pleural-fluid-gram-done'])
                ->add('pleuralFluidGramResult', GramStain::class, ['required' => false, 'hidden' => ['parent' => 'pleuralFluidGramDone', 'child'=>'pleuralFluidGramResult','value'=>TripleChoice::YES], 'label'=>'ibd-form.pleural-fluid-gram-result'])
                ->add('pleuralFluidGramResultOrganism', GramStainResult::class, ['required' => false, 'hidden' => ['parent' => 'pleuralFluidGramResult', 'value' => json_encode([GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE])], 'label'=>'ibd-form.pleural-fluid-gram-result-organism'])
                ->add('pleuralFluidPcrDone', TripleChoice::class, ['required' => false, 'hidden' => ['child' => 'pleuralFluidPcrDone'], 'label'=>'ibd-form.pleural-fluid-pcr-done'])
                ->add('pleuralFluidPcrResult', PCRResult::class, ['required' => false,'hidden' => ['parent' => 'pleuralFluidPcrDone', 'child' => 'pleuralFluidPcrResult', 'value' => TripleChoice::YES], 'label'=>'ibd-form.pleural-fluid-pcr-result'])
                ->add('pleuralFluidPcrOther', null, ['required' => false, 'hidden' => ['parent' => 'pleuralFluidPcrResult', 'value' => PCRResult::OTHER], 'label'=>'ibd-form.pleural-fluid-pcr-other']);
        }

        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'postSetData']);
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
                    ->add('sentToReferenceLab', SwitchType::class, ['label' => 'Sent To Reference Lab', 'required' => false, 'hidden-child' => 'sentToReferenceLab', 'switch_type' => 2])
                    ->add('csfSentToRRLDate', DatePickerType::class, ['label' => 'ibd-form.csf-sent-to-rrl-date', 'required' => false, 'hidden-parent' => 'sentToReferenceLab', 'hidden-value' => 1])
                    ->add('csfIsolSentToRRLDate', DatePickerType::class, ['label' => 'ibd-form.csf-isol-sent-to-rrl-date', 'required' => false, 'hidden-parent' => 'sentToReferenceLab', 'hidden-value' => 1])
                    ->add('bloodIsolSentToRRLDate', DatePickerType::class, ['label' => 'ibd-form.blood-sent-to-rrl-date', 'required' => false, 'hidden-parent' => 'sentToReferenceLab', 'hidden-value' => 1])
                    ->add('brothSentToRRLDate', DatePickerType::class, ['label' => 'ibd-form.broth-sent-to-rrl-date', 'required' => false, 'hidden-parent' => 'sentToReferenceLab', 'hidden-value' => 1]);
            }

            if ($country->hasNationalLab()) {
                $form->add('sentToNationalLab', SwitchType::class, ['required' => false, 'label' => 'Sent To National Lab', 'switch_type' => 2]);
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SiteLab::class,
        ]);
    }
}
