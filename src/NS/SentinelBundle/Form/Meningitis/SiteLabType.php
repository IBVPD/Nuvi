<?php

namespace NS\SentinelBundle\Form\Meningitis;

use NS\AceBundle\Form\DatePickerType;
use NS\AceBundle\Form\SwitchType;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Meningitis\SiteLab;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Form\IBD\Types\BinaxResult;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\IBD\Types\GramStain;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use NS\SentinelBundle\Form\IBD\Types\LatResult;
use NS\SentinelBundle\Form\IBD\Types\PCRResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Services\SerializedSites;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SiteLabType extends AbstractType
{
    /** @var SerializedSites */
    private $siteSerializer;

    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    public function __construct(SerializedSites $siteSerializer, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->siteSerializer = $siteSerializer;
        $this->authChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isPaho = $this->authChecker->isGranted('ROLE_AMR');

        $builder
            ->add('csfLabDate',         DatePickerType::class, ['required' => false, 'label' => 'ibd-form.csf-lab-datetime', 'property_path' => 'csf_lab_date'])
            ->add('csfLabTime',         TimeType::class, ['required' => false, 'label' => 'ibd-form.csf-lab-time','minutes'=>[0,5,10,15,20,25,30,35,40,45,50,55], 'property_path' => 'csf_lab_time'])
            ->add('csfId',              null, ['required' => false, 'label' => 'ibd-form.csf-id'])
            ->add('csfWcc',             IntegerType::class, ['required' => false, 'label' => 'ibd-form.csf-wcc', 'property_path' => 'csf_wcc', 'attr' => ['min' => 0]])
            ->add('csfGlucose',         IntegerType::class, ['required' => false, 'label' => 'ibd-form.csf-glucose', 'property_path' => 'csf_glucose', 'attr' => ['min' => 0]])
            ->add('csfProtein',         IntegerType::class, ['required' => false, 'label' => 'ibd-form.csf-protein', 'property_path' => 'csf_protein', 'attr' => ['min' => 0]])
            ->add('csfCultDone',        TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-cult-done'])
            ->add('csfCultResult',      CultureResult::class, [
                'required' => false,
                'label' => 'ibd-form.csf-cult-result',
                'exclude_choices'=> $isPaho ? [CultureResult::UNKNOWN]:null,
                'hidden' => [
                    'parent' => 'csfCultDone',
                    'value' => TripleChoice::YES]
            ])
            ->add('csfCultOther',       null, ['required' => false, 'label' => 'ibd-form.csf-culture-other', 'hidden' => ['parent' => 'csfCultResult', 'value' => CultureResult::OTHER]])
            ->add('csfCultContaminant', null, ['required' => false, 'label' => 'ibd-form.csf-culture-contaminant', 'hidden' => ['parent' => 'csfCultResult', 'value' => CultureResult::CONTAMINANT]])
            ->add('csfGramDone',        TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-gram-done'])
            ->add('csfGramStain',       GramStain::class, [
                'required' => false,
                'label' => 'ibd-form.csf-gram-result',
                'hidden' => ['parent' => 'csfGramDone', 'value' => TripleChoice::YES],
                'exclude_choices' => $isPaho ? [GramStain::UNKNOWN] : [],
            ])
            ->add('csfGramResult',      GramStainResult::class, [
                'required' => false,
                'label' => 'ibd-form.csf-gram-result-organism',
                'hidden' => ['parent' => 'csfGramStain', 'value' => [GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE]],
            ])
            ->add('csfGramOther',       null, ['required' => false, 'label' => 'ibd-form.csf-gram-other', 'hidden' => ['parent' => 'csfGramResult', 'value' => GramStainResult::OTHER]])
            ->add('csfBinaxDone',       TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-binax-done'])
            ->add('csfBinaxResult',     BinaxResult::class, [
                'required' => false,
                'label' => 'ibd-form.csf-binax-result',
                'hidden' => ['parent' => 'csfBinaxDone', 'value' => TripleChoice::YES],
                'exclude_choices' => $isPaho ? [BinaxResult::UNKNOWN, BinaxResult::INCONCLUSIVE] : [],
            ])
            ->add('csfLatDone',         TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-lat-done', 'exclude_choices'=> $isPaho ? [TripleChoice::UNKNOWN]:null])
            ->add('csfLatResult',       LatResult::class, [
                'required' => false,
                'label' => 'ibd-form.csf-lat-result',
                'hidden' => ['parent' => 'csfLatDone', 'value' => TripleChoice::YES],
                'exclude_choices' => $isPaho ? [LatResult::UNKNOWN]:[],
            ])
            ->add('csfLatOther',        null, ['required' => false, 'label' => 'ibd-form.csf-lat-other', 'hidden' => ['parent' => 'csfLatResult', 'value' => LatResult::OTHER]])
            ->add('csfPcrDone',         TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-pcr-done'])
            ->add('csfPcrResult',       PCRResult::class, ['required' => false, 'label' => 'ibd-form.csf-pcr-result', 'hidden' => ['parent' => 'csfPcrDone', 'value' => TripleChoice::YES]])
            ->add('csfPcrOther',        null, ['required' => false, 'label' => 'ibd-form.csf-pcr-other', 'hidden' => ['parent' => 'csfPcrResult', 'value' => PCRResult::OTHER]])
            ->add('csfStore',           TripleChoice::class, ['required' => false, 'label' => 'ibd-form.csf-store'])
            ->add('isolStore',          TripleChoice::class, ['required' => false, 'label' => 'ibd-form.isol-store'])
            ->add('bloodId',            null, ['required' => false, 'label' => 'ibd-form.blood-id'])
            ->add('bloodLabDate',       DatePickerType::class, ['required' => false, 'label' => 'ibd-form.blood-lab-datetime'])
            ->add('bloodLabTime',       TimeType::class, ['required' => false, 'label' => 'ibd-form.blood-lab-time','minutes'=>[0,5,10,15,20,25,30,35,40,45,50,55]])
            ->add('bloodCultDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-cult-done', 'exclude_choices'=> $isPaho ? [TripleChoice::UNKNOWN]:null])
            ->add('bloodCultResult',    CultureResult::class, ['required' => false, 'label' => 'ibd-form.blood-cult-result', 'hidden' => ['parent' => 'bloodCultDone','child'=>'bloodCultResult', 'value' => TripleChoice::YES],'exclude_choices'=> $isPaho?[CultureResult::UNKNOWN]:null])
            ->add('bloodCultOther',     null, ['required' => false, 'label' => 'ibd-form.blood-cult-other', 'hidden' => ['parent' => 'bloodCultResult', 'value' => CultureResult::OTHER]])
            ->add('bloodGramDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-gram-done', 'exclude_choices'=> $isPaho ? [TripleChoice::UNKNOWN]:[]])
            ->add('bloodGramStain',     GramStain::class, ['required' => false, 'label' => 'ibd-form.blood-gram-result', 'hidden' => ['parent' => 'bloodGramDone', 'value' => TripleChoice::YES], 'exclude_choices' => $isPaho ? [GramStain::UNKNOWN]:[]])
            ->add('bloodGramResult',    GramStainResult::class, ['required' => false, 'label' => 'ibd-form.blood-gram-result-organism', 'hidden' => ['parent' => 'bloodGramStain', 'value' => [GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE]], 'exclude_choices'=> $isPaho ? [GramStainResult::UNKNOWN]:[]])
            ->add('bloodGramOther',     null, ['required' => false, 'label' => 'ibd-form.blood-gram-other', 'hidden' => ['parent' => 'bloodGramResult', 'value' => GramStainResult::OTHER]])
            ->add('bloodPcrDone',       TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-pcr-done'])
            ->add('bloodPcrResult',     PCRResult::class, ['required' => false, 'label' => 'ibd-form.blood-pcr-result', 'hidden' => ['parent' => 'bloodPcrDone', 'value' => TripleChoice::YES]])
            ->add('bloodPcrOther',      null, ['required' => false, 'label' => 'ibd-form.blood-pcr-other', 'hidden' => ['parent' => 'bloodPcrResult', 'value' => PCRResult::OTHER]])
            ->add('otherId',            null, ['required' => false, 'label' => 'ibd-form.other-id'])
            ->add('otherType',          null, ['required' => false, 'label' => 'ibd-form.other-type'])
            ->add('otherLabDate',       DatePickerType::class, ['required' => false, 'label' => 'ibd-form.other-lab-datetime'])
            ->add('otherLabTime',       TimeType::class, ['required' => false, 'label' => 'ibd-form.other-lab-time','minutes'=>[0,5,10,15,20,25,30,35,40,45,50,55]])
            ->add('otherCultDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.other-cult-done1'])
            ->add('otherCultResult',    CultureResult::class, ['required' => false, 'label' => 'ibd-form.other-cult-result', 'hidden' => ['parent' => 'otherCultDone', 'value' => TripleChoice::YES]])
            ->add('otherCultOther',     null, ['required' => false, 'label' => 'ibd-form.other-cult-other', 'hidden' => ['parent' => 'otherCultResult', 'value' => CultureResult::OTHER]])
            ->add('otherTestDone',      TripleChoice::class, ['required' => false, 'label' => 'ibd-form.other-test-done1'])
            ->add('otherTestResult',    CultureResult::class, ['required' => false, 'label' => 'ibd-form.other-test-result', 'hidden' => ['parent' => 'otherTestDone', 'value' => TripleChoice::YES]])
            ->add('otherTestOther',     null, ['required' => false, 'label' => 'ibd-form.other-test-other', 'hidden' => ['parent' => 'otherTestResult', 'value' => CultureResult::OTHER]])
        ;

        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'postSetData']);
    }

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
                ->add('nlCsfSent', SwitchType::class, ['label' => 'ibd-form.csf-sent-to-nl', 'required' => false, 'switch_type' => 2])
                ->add('nlCsfDate', DatePickerType::class, ['label' => 'ibd-form.csf-sent-to-nl-date', 'required' => false, 'hidden' => ['parent' => 'nlCsfSent', 'value' => 1]])
                ->add('nlIsolCsfSent', SwitchType::class, ['label' => 'ibd-form.csf-isol-sent-to-nl', 'required' => false, 'switch_type' => 2])
                ->add('nlIsolCsfDate', DatePickerType::class, ['label' => 'ibd-form.csf-isol-sent-to-nl-date', 'required' => false, 'hidden' => ['parent' => 'nlIsolCsfSent', 'value' => 1]])
                ->add('nlIsolBloodSent', SwitchType::class, ['label' => 'ibd-form.blood-sent-to-nl', 'required' => false, 'switch_type' => 2])
                ->add('nlIsolBloodDate', DatePickerType::class, ['label' => 'ibd-form.blood-sent-to-nl-date', 'required' => false, 'hidden' => ['parent' => 'nlIsolBloodSent', 'value' => 1]])
                ->add('nlOtherSent', SwitchType::class, ['label' => 'ibd-form.other-sent-to-nl', 'required' => false, 'switch_type' => 2])
                ->add('nlOtherDate', DatePickerType::class, ['label' => 'ibd-form.other-sent-to-nl-date', 'required' => false, 'hidden' => ['parent' => 'nlOtherSent', 'value' => 1]]);
        }

        if ($isPaho) {
            $form
                ->add('bloodSecondId', null, ['required' => false, 'label' => 'ibd-form.blood-id'])
                ->add('bloodSecondLabDate', DatePickerType::class, ['required' => false, 'label' => 'ibd-form.blood-lab-datetime'])
                ->add('bloodSecondLabTime', TimeType::class, ['required' => false, 'label' => 'ibd-form.blood-lab-time', 'minutes' => [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55]])
                ->add('bloodSecondCultDone', TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-cult-done', 'exclude_choices' => $isPaho ? [TripleChoice::UNKNOWN] : null])
                ->add('bloodSecondCultResult', CultureResult::class, ['required' => false, 'label' => 'ibd-form.blood-cult-result', 'hidden' => ['parent' => 'bloodSecondCultDone', 'child' => 'bloodSecondCultResult', 'value' => TripleChoice::YES], 'exclude_choices' => $isPaho ? [CultureResult::UNKNOWN] : null])
                ->add('bloodSecondCultOther', null, ['required' => false, 'label' => 'ibd-form.blood-cult-other', 'hidden' => ['parent' => 'bloodSecondCultResult', 'value' => CultureResult::OTHER]])
                ->add('bloodSecondGramDone', TripleChoice::class, ['required' => false, 'label' => 'ibd-form.blood-gram-done', 'exclude_choices'=> $isPaho ? [TripleChoice::UNKNOWN]:[]])
                ->add('bloodSecondGramStain', GramStain::class, ['required' => false, 'label' => 'ibd-form.blood-gram-result', 'hidden' => ['parent' => 'bloodSecondGramDone', 'value' => TripleChoice::YES], 'exclude_choices' => $isPaho ? [GramStain::UNKNOWN]:[]])
                ->add('bloodSecondGramResult', GramStainResult::class, ['required' => false, 'label' => 'ibd-form.blood-gram-result-organism', 'hidden' => ['parent' => 'bloodSecondGramStain', 'value' => [GramStain::GM_NEGATIVE, GramStain::GM_POSITIVE]], 'exclude_choices'=> $isPaho ? [GramStainResult::UNKNOWN]:[]])
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
