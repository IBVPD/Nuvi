<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\RotaVirus\SiteLab;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Services\SerializedSites;
use Symfony\Component\Form\AbstractType;
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

    public function __construct(SerializedSites $siteSerializer, AuthorizationCheckerInterface $authChecker)
    {
        $this->siteSerializer = $siteSerializer;
        $this->authChecker    = $authChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isPaho = $this->authChecker->isGranted('ROLE_AMR');

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
            ->add('elisaResult', ElisaResult::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-result', 'hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES]]);

        if (!$isPaho) {
            $builder
                ->add('genotypingDate', DatePickerType::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-genotyping-date',])
                ->add('genotypingResultG', GenotypeResultG::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-genotyping-result-g'])
                ->add('genotypingResultGSpecify', null, ['required' => false, 'label' => 'rotavirus-form.site-lab-genotyping-result-g-specify', 'hidden' => ['parent' => 'genotypingResultG', 'value' => GenotypeResultG::OTHER]])
                ->add('genotypeResultP', GenotypeResultP::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-genotype-result-p'])
                ->add('genotypeResultPSpecify', null, ['required' => false, 'label' => 'rotavirus-form.site-lab-genotype-result-p-specify', 'hidden' => ['parent' => 'genotypeResultP', 'value' => GenotypeResultP::OTHER]]);
        }

        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'postSetData']);
    }

    public function postSetData(FormEvent $event): void
    {
        $data    = $event->getData();
        $form    = $event->getForm();
        $country = null;

        if ($data && $data->getCaseFile() && $data->getCaseFile()->getCountry()) {
            $country = $data->getCaseFile()->getCountry();
        } elseif (!$this->siteSerializer->hasMultipleSites()) {
            $site    = $this->siteSerializer->getSite();
            $country = ($site instanceof Site) ? $site->getCountry() : null;
        }

        if (($country instanceof Country) && $country->hasNationalLab()) {
            $form
                ->add('stoolSentToNL', TripleChoice::class, ['required' => false, 'label' => 'rotavirus-form.stoolSentToNL'])
                ->add('stoolSentToNLDate', DatePickerType::class, ['required' => false, 'label' => 'rotavirus-form.stoolSentToNLDate', 'hidden' => ['parent' => 'stoolSentToNL', 'value' => TripleChoice::YES]]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SiteLab::class,
        ]);
    }
}
