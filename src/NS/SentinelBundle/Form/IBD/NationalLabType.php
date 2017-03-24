<?php

namespace NS\SentinelBundle\Form\IBD;

use NS\AceBundle\Form\SwitchType;
use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\IBD\NationalLab;
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

    /**
     * NationalLabType constructor.
     * @param SerializedSites $siteSerializer
     */
    public function __construct(SerializedSites $siteSerializer)
    {
        $this->siteSerializer = $siteSerializer;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA,[$this,'postSetData']);
    }

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
                    ->add('rlIsolCsfSent', SwitchType::class, ['label' => 'ibd-form.csf-isol-sent-to-rrl', 'required' => false])
                    ->add('rlIsolCsfDate', DatePickerType::class, ['label' => 'ibd-form.csf-isol-sent-to-rrl-date', 'required' => false, 'hidden' => ['parent' => 'rlIsolCsfSent', 'value' => 1]])
                    ->add('rlIsolBloodSent', SwitchType::class, ['label' => 'ibd-form.blood-sent-to-rrl', 'required' => false])
                    ->add('rlIsolBloodDate', DatePickerType::class, ['label' => 'ibd-form.blood-sent-to-rrl-date', 'required' => false, 'hidden' => ['parent' => 'rlIsolBloodSent', 'value' => 1]])
                    ->add('rlOtherSent', SwitchType::class, ['label' => 'ibd-form.other-sent-to-rrl', 'required' => false])
                    ->add('rlOtherDate', DatePickerType::class, ['label' => 'ibd-form.other-sent-to-rrl-date', 'required' => false, 'hidden' => ['parent' => 'rlOtherSent', 'value' => 1]]);
            }

        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NationalLab::class
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return BaseLabType::class;
    }
}
