<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Entity\RotaVirus\SiteLab;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use NS\SentinelBundle\Services\SerializedSites;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
use NS\SentinelBundle\Entity\Site;

class SiteLabType extends AbstractType
{
    /** @var SerializedSites */
    private $siteSerializer;

    public function __construct(SerializedSites $siteSerializer)
    {
        $this->siteSerializer = $siteSerializer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('received', DatePickerType::class, ['required' => true, 'label' => 'rotavirus-form.site-lab-sample-received'])
            ->add('adequate', TripleChoice::class, ['required' => true, 'label' => 'rotavirus-form.site-lab-adequate'])
            ->add('stored', TripleChoice::class, ['required' => true, 'label' => 'rotavirus-form.site-lab-stored'])
            ->add('elisaDone', TripleChoice::class, ['required' => true, 'label' => 'rotavirus-form.site-lab-elisa-done'])
            ->add('elisaKit', ElisaKit::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-elisa-kit', 'hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES]])
            ->add('elisaKitOther', null, ['required' => false, 'label' => 'rotavirus-form.site-lab-elisa-kit-other', 'hidden' => ['parent' => 'elisaKit', 'value' => ElisaKit::OTHER]])
            ->add('elisaLoadNumber', null, ['required' => false, 'label' => 'rotavirus-form.site-lab-elisa-load-number', 'hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES]])
            ->add('elisaExpiryDate', DatePickerType::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-elisa-kit-expiry-date', 'hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES]])
            ->add('elisaTestDate', DatePickerType::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-test-date', 'hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES]])
            ->add('elisaResult', ElisaResult::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-result', 'hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES]])
            ->add('genotypingDate', DatePickerType::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-genotyping-date',])
            ->add('genotypingResultG', GenotypeResultG::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-genotyping-result-g'])
            ->add('genotypingResultGSpecify', null, ['required' => false, 'label' => 'rotavirus-form.site-lab-genotyping-result-g-specify', 'hidden' => ['parent' => 'genotypingResultG', 'value' => GenotypeResultG::OTHER]])
            ->add('genotypeResultP', GenotypeResultP::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-genotype-result-p'])
            ->add('genotypeResultPSpecify', null, ['required' => false, 'label' => 'rotavirus-form.site-lab-genotype-result-p-specify', 'hidden' => ['parent' => 'genotypeResultP', 'value' => GenotypeResultP::OTHER]]);

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

        if ($country instanceof Country) {
//            if ($country->hasReferenceLab()) {
//                $form
//                    ->add('stoolSentToRRL', TripleChoice::class, ['required' => false, 'label' => 'rotavirus-form.stoolSentToRRL'])
//                    ->add('stoolSentToRRLDate', DatePickerType::class, ['required' => false, 'label' => 'rotavirus-form.stoolSentToRRLDate', 'hidden' => ['parent' => 'stoolSentToRRL', 'value' => TripleChoice::YES]]);
//            }

            if ($country->hasNationalLab()) {
                $form
                    ->add('stoolSentToNL', TripleChoice::class, ['required' => false, 'label' => 'rotavirus-form.stoolSentToNL'])
                    ->add('stoolSentToNLDate', DatePickerType::class, ['required' => false, 'label' => 'rotavirus-form.stoolSentToNLDate', 'hidden' => ['parent' => 'stoolSentToNL', 'value' => TripleChoice::YES]]);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SiteLab::class
        ]);
    }
}
