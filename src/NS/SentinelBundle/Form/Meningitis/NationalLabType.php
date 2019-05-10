<?php

namespace NS\SentinelBundle\Form\Meningitis;

use NS\AceBundle\Form\DatePickerType;
use NS\AceBundle\Form\SwitchType;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Meningitis\NationalLab;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Services\SerializedSites;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NationalLabType extends AbstractType
{
    /** @var SerializedSites */
    private $siteSerializer;

    public function __construct(SerializedSites $siteSerializer)
    {
        $this->siteSerializer = $siteSerializer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA,[$this,'postSetData']);
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
            if ($country->hasReferenceLab()) {
                $form
                    ->add('rlIsolCsfSent', SwitchType::class, ['label' => 'ibd-form.csf-isol-sent-to-rrl', 'required' => false, 'switch_type' => 2])
                    ->add('rlIsolCsfDate', DatePickerType::class, ['label' => 'ibd-form.csf-isol-sent-to-rrl-date', 'required' => false, 'hidden' => ['parent' => 'rlIsolCsfSent', 'value' => 1]])
                    ->add('rlIsolBloodSent', SwitchType::class, ['label' => 'ibd-form.blood-sent-to-rrl', 'required' => false, 'switch_type' => 2])
                    ->add('rlIsolBloodDate', DatePickerType::class, ['label' => 'ibd-form.blood-sent-to-rrl-date', 'required' => false, 'hidden' => ['parent' => 'rlIsolBloodSent', 'value' => 1]])
                    ->add('rlOtherSent', SwitchType::class, ['label' => 'ibd-form.other-sent-to-rrl', 'required' => false, 'switch_type' => 2])
                    ->add('rlOtherDate', DatePickerType::class, ['label' => 'ibd-form.other-sent-to-rrl-date', 'required' => false, 'hidden' => ['parent' => 'rlOtherSent', 'value' => 1]]);
            }

        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NationalLab::class
        ]);
    }

    public function getParent(): string
    {
        return BaseLabType::class;
    }
}
