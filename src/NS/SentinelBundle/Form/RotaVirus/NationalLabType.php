<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\SentinelBundle\Entity\RotaVirus\NationalLab;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use NS\AceBundle\Form\DatePickerType;

/**
 * Class NationalLabType
 * @package NS\SentinelBundle\Form\Rota
 */
class NationalLabType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('elisaDone', TripleChoice::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-elisa-done'])
            ->add('elisaKit', ElisaKit::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-elisa-kit', 'hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES]])
            ->add('elisaKitOther', null, ['required' => false, 'label' => 'rotavirus-form.site-lab-elisa-kit-other', 'hidden' => ['parent' => 'elisaKit', 'value' => ElisaKit::OTHER]])
            ->add('elisaLoadNumber', null, ['required' => false, 'label' => 'rotavirus-form.site-lab-elisa-load-number', 'hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES]])
            ->add('elisaExpiryDate', DatePickerType::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-elisa-kit-expiry-date', 'hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES]])
            ->add('elisaTestDate', DatePickerType::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-test-date', 'hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES]])
            ->add('elisaResult', ElisaResult::class, ['required' => false, 'label' => 'rotavirus-form.site-lab-result', 'hidden' => ['parent' => 'elisaDone', 'value' => TripleChoice::YES]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NationalLab::class,
            'error_bubbling' => false,
        ]);
    }

    public function getParent()
    {
        return BaseLabType::class;
    }
}
