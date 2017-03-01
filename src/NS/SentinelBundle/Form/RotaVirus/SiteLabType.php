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
        $attr1p1 = [
            'hidden' => [
                'parent' => 'elisaDone',
                'value'  => TripleChoice::YES]
        ];

        $builder
            ->add('received',           DatePickerType::class)
            ->add('adequate',           TripleChoice::class)
            ->add('stored',             TripleChoice::class)
            ->add('elisaDone',          TripleChoice::class)
            ->add('elisaKit',           ElisaKit::class, ['hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES],'placeholder'=> ' '])
            ->add('elisaKitOther',      null, ['hidden' => ['parent' => 'elisaKit', 'value' => ElisaKit::OTHER]])
            ->add('elisaLoadNumber',    null, $attr1p1)
            ->add('elisaExpiryDate',    DatePickerType::class, $attr1p1)
            ->add('elisaTestDate',      DatePickerType::class, $attr1p1)
            ->add('elisaResult',        ElisaResult::class, ['hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES],'placeholder'=> ' '])
            ->add('genotypingDate',     DatePickerType::class)
            ->add('genotypingResultG',  GenotypeResultG::class,['placeholder'=>' '])
            ->add('genotypingResultGSpecify', null, ['hidden' => ['parent' => 'genotypingResultG', 'value' => GenotypeResultG::OTHER]])
            ->add('genotypeResultP',    GenotypeResultP::class, ['placeholder' => ' '])
            ->add('genotypeResultPSpecify', null, ['hidden' => ['parent' => 'genotypeResultP', 'value' => GenotypeResultP::OTHER]]);

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
                    ->add('stoolSentToRRL', TripleChoice::class, ['required' => false, 'label' => 'rotavirus-form.stoolSentToRRL'])
                    ->add('stoolSentToRRLDate', DatePickerType::class, ['required' => false, 'label' => 'rotavirus-form.stoolSentToRRLDate', 'hidden' => ['parent' => 'stoolSentToRRL', 'value' => TripleChoice::YES]]);
            }

            if ($country->hasNationalLab()) {
                $form
                    ->add('stoolSentToNL', TripleChoice::class, ['required' => false, 'label' => 'rotavirus-form.stoolSentToNL'])
                    ->add('stoolSentToNLDate', DatePickerType::class, ['required' => false, 'label' => 'rotavirus-form.stoolSentToNLDate', 'hidden' => ['parent' => 'stoolSentToNL', 'value' => TripleChoice::YES]]);
            }
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SiteLab::class
        ]);
    }
}
