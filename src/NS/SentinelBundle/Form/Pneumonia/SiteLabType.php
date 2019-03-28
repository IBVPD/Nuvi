<?php

namespace NS\SentinelBundle\Form\Pneumonia;

use NS\AceBundle\Form\DatePickerType;
use NS\AceBundle\Form\SwitchType;
use NS\SentinelBundle\Entity\Pneumonia\SiteLab;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Services\SerializedSites;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\IBD\Types\PCRResult;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\IBD\Types\GramStain;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use NS\SentinelBundle\Form\IBD\Types\BinaxResult;
use NS\SentinelBundle\Form\IBD\Types\LatResult;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class SiteLabType
 * @package NS\SentinelBundle\Form\Pneumonia
 */
class SiteLabType extends AbstractType
{
    /** @var SerializedSites */
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

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isPaho = $this->authChecker->isGranted('ROLE_AMR');

        $builder
            ->add('bloodId',            null, ['required' => false, 'label' => 'ibd-form.blood-id'])
            ->add('bloodLabDate',       DatePickerType::class, ['required' => false, 'label' => 'ibd-form.blood-lab-datetime'])
            ->add('bloodLabTime',       TimeType::class, ['required' => false, 'label' => 'ibd-form.blood-lab-time','minutes'=>[0,5,10,15,20,25,30,35,40,45,50,55]])
            ->add('bloodCultDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-cult-done', 'exclude_choices'=> ($isPaho ? [TripleChoice::UNKNOWN]:null)])
            ->add('bloodCultResult',    CultureResult::class, ['required' => false, 'label' => 'ibd-form.blood-cult-result', 'hidden' => ['parent' => 'bloodCultDone','child'=>'bloodCultResult', 'value' => TripleChoice::YES],'exclude_choices'=>($isPaho?[CultureResult::UNKNOWN]:null)])
            ->add('bloodCultOther',     null, ['required' => false, 'label' => 'ibd-form.blood-cult-other', 'hidden' => ['parent' => 'bloodCultResult', 'value' => CultureResult::OTHER]])
            ->add('bloodGramDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-gram-done' ])
            ->add('bloodGramStain',     GramStain::class, ['required' => false, 'label' => 'ibd-form.blood-gram-result', 'hidden' => ['parent' => 'bloodGramDone', 'value' => TripleChoice::YES]])
            ->add('bloodGramResult',    GramStainResult::class, ['required' => false, 'label' => 'ibd-form.blood-gram-result-organism', 'hidden' => ['parent' => 'bloodGramStain', 'value' => [GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE]]])
            ->add('bloodGramOther',     null, ['required' => false, 'label' => 'ibd-form.blood-gram-other', 'hidden' => ['parent' => 'bloodGramResult', 'value' => GramStainResult::OTHER]])
            ->add('bloodPcrDone',       TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-pcr-done'])
            ->add('bloodPcrResult',     PCRResult::class, ['required' => false, 'label' => 'ibd-form.blood-pcr-result', 'hidden' => ['parent' => 'bloodPcrDone', 'value' => TripleChoice::YES]])
            ->add('bloodPcrOther',      null, ['required' => false, 'label' => 'ibd-form.blood-pcr-other', 'hidden' => ['parent' => 'bloodPcrResult', 'value' => PCRResult::OTHER]])
            ->add('otherId',            null, ['required' => false, 'label' => 'ibd-form.other-id'])
            ->add('otherType',            null, ['required' => false, 'label' => 'ibd-form.other-type'])
            ->add('otherLabDate',       DatePickerType::class, ['required' => false, 'label' => 'ibd-form.other-lab-datetime'])
            ->add('otherLabTime',       TimeType::class, ['required' => false, 'label' => 'ibd-form.other-lab-time','minutes'=>[0,5,10,15,20,25,30,35,40,45,50,55]])
            ->add('otherCultDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.other-cult-done1'])
            ->add('otherCultResult',    CultureResult::class, ['required' => false, 'label' => 'ibd-form.other-cult-result', 'hidden' => ['parent' => 'otherCultDone', 'value' => TripleChoice::YES]])
            ->add('otherCultOther',     null, ['required' => false, 'label' => 'ibd-form.other-cult-other', 'hidden' => ['parent' => 'otherCultResult', 'value' => CultureResult::OTHER]])
            ->add('otherTestDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.other-test-done1'])
            ->add('otherTestResult',    CultureResult::class, ['required' => false, 'label' => 'ibd-form.other-test-result', 'hidden' => ['parent' => 'otherTestDone', 'value' => TripleChoice::YES]])
            ->add('otherTestOther',     null, ['required' => false, 'label' => 'ibd-form.other-test-other', 'hidden' => ['parent' => 'otherTestResult', 'value' => CultureResult::OTHER]])
        ;

        if ($isPaho) {
            $builder
                ->add('pleuralFluidCultureDone', TripleChoice::class, ['required' => false, 'label'=>'ibd-form.pleural-fluid-culture-done'])
                ->add('pleuralFluidCultureResult', CultureResult::class, ['required' => false, 'hidden' => ['parent' => 'pleuralFluidCultureDone', 'value' => TripleChoice::YES], 'label'=>'ibd-form.pleural-fluid-culture-result'])
                ->add('pleuralFluidCultureOther', null, ['required' => false, 'hidden' => ['parent' => 'pleuralFluidCultureResult', 'value' => CultureResult::OTHER], 'label'=>'ibd-form.pleural-fluid-culture-result-other'])
                ->add('pleuralFluidGramDone', TripleChoice::class, ['required' => false, 'label'=>'ibd-form.pleural-fluid-gram-done'])
                ->add('pleuralFluidGramResult', GramStain::class, ['required' => false, 'hidden' => ['parent' => 'pleuralFluidGramDone', 'value'=>TripleChoice::YES], 'label'=>'ibd-form.pleural-fluid-gram-result'])
                ->add('pleuralFluidGramResultOrganism', GramStainResult::class, ['required' => false, 'hidden' => ['parent' => 'pleuralFluidGramResult', 'value' => [GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE]], 'label'=>'ibd-form.pleural-fluid-gram-result-organism'])
                ->add('pleuralFluidPcrDone', TripleChoice::class, ['required' => false, 'label'=>'ibd-form.pleural-fluid-pcr-done'])
                ->add('pleuralFluidPcrResult', PCRResult::class, ['required' => false,'hidden' => ['parent' => 'pleuralFluidPcrDone', 'value' => TripleChoice::YES], 'label'=>'ibd-form.pleural-fluid-pcr-result'])
                ->add('pleuralFluidPcrOther', null, ['required' => false, 'hidden' => ['parent' => 'pleuralFluidPcrResult', 'value' => PCRResult::OTHER], 'label'=>'ibd-form.pleural-fluid-pcr-other']);
        }

        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'postSetData']);
    }

    /**
     * @param FormEvent $event
     */
    public function postSetData(FormEvent $event): void
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

        $region  = ($country instanceof Country) ? $country->getRegion(): null;

        $isPaho = ($region && $region->getCode() === 'AMR') || $this->authChecker->isGranted('ROLE_AMR');

        if (($country instanceof Country) && $country->hasNationalLab()) {
            $form
                ->add('nlIsolBloodSent', SwitchType::class, ['label' => 'ibd-form.blood-sent-to-nl', 'required' => false])
                ->add('nlIsolBloodDate', DatePickerType::class, ['label' => 'ibd-form.blood-sent-to-nl-date', 'required' => false, 'hidden' => ['parent' => 'nlIsolBloodSent', 'value' => 1]])
                ->add('nlOtherSent', SwitchType::class, ['label' => 'ibd-form.other-sent-to-nl', 'required' => false])
                ->add('nlOtherDate', DatePickerType::class, ['label' => 'ibd-form.other-sent-to-nl-date', 'required' => false, 'hidden' => ['parent' => 'nlOtherSent', 'value' => 1]]);
        }

        if ($isPaho) {
            $form
                ->add('bloodSecondId', null, ['required' => false, 'label' => 'ibd-form.blood-id'])
                ->add('bloodSecondLabDate', DatePickerType::class, ['required' => false, 'label' => 'ibd-form.blood-lab-datetime'])
                ->add('bloodSecondLabTime', TimeType::class, ['required' => false, 'label' => 'ibd-form.blood-lab-time', 'minutes' => [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55]])
                ->add('bloodSecondCultDone', TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-cult-done', 'exclude_choices' => ($isPaho ? [TripleChoice::UNKNOWN] : null)])
                ->add('bloodSecondCultResult', CultureResult::class, ['required' => false, 'label' => 'ibd-form.blood-cult-result', 'hidden' => ['parent' => 'bloodSecondCultDone', 'child' => 'bloodSecondCultResult', 'value' => TripleChoice::YES], 'exclude_choices' => ($isPaho ? [CultureResult::UNKNOWN] : null)])
                ->add('bloodSecondCultOther', null, ['required' => false, 'label' => 'ibd-form.blood-cult-other', 'hidden' => ['parent' => 'bloodSecondCultResult', 'value' => CultureResult::OTHER]])
                ->add('bloodSecondGramDone', TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-gram-done'])
                ->add('bloodSecondGramStain', GramStain::class, ['required' => false, 'label' => 'ibd-form.blood-gram-result', 'hidden' => ['parent' => 'bloodSecondGramDone', 'value' => TripleChoice::YES]])
                ->add('bloodSecondGramResult', GramStainResult::class, ['required' => false, 'label' => 'ibd-form.blood-gram-result-organism', 'hidden' => ['parent' => 'bloodSecondGramStain', 'value' => [GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE]]])
                ->add('bloodSecondGramOther', null, ['required' => false, 'label' => 'ibd-form.blood-gram-other', 'hidden' => ['parent' => 'bloodSecondGramResult', 'value' => GramStainResult::OTHER]])
                ->add('bloodSecondPcrDone', TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-pcr-done'])
                ->add('bloodSecondPcrResult', PCRResult::class, ['required' => false, 'label' => 'ibd-form.blood-pcr-result', 'hidden' => ['parent' => 'bloodSecondPcrDone', 'value' => TripleChoice::YES]])
                ->add('bloodSecondPcrOther', null, ['required' => false, 'label' => 'ibd-form.blood-pcr-other', 'hidden' => ['parent' => 'bloodSecondPcrResult', 'value' => PCRResult::OTHER]]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SiteLab::class,
        ]);
    }
}
