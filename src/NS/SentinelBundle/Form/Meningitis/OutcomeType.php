<?php

namespace NS\SentinelBundle\Form\Meningitis;

use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeOutcome;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class OutcomeType extends AbstractType
{
    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isPaho = $this->authChecker->isGranted('ROLE_AMR');

        $builder
            ->add('dischOutcome', DischargeOutcome::class, ['required' => false, 'label' => 'ibd-form.discharge-outcome', 'exclude_choices' => $isPaho ? [DischargeOutcome::UNKNOWN]:[]])
            ->add('dischDx', DischargeDiagnosis::class, ['required' => false, 'label' => 'ibd-form.discharge-diagnosis', 'exclude_choices' => $isPaho ? [DischargeDiagnosis::UNKNOWN, DischargeDiagnosis::SEPSIS]:null])
            ->add('dischDxOther', null, ['required' => false, 'label' => 'ibd-form.discharge-diagnosis-other', 'hidden' => ['parent' => 'dischDx', 'value' => [DischargeDiagnosis::OTHER,DischargeDiagnosis::OTHER_MENINGITIS,DischargeDiagnosis::OTHER_PNEUMONIA]]])
            ->add('dischClass', DischargeClassification::class, [
                'required' => false,
                'label' => 'ibd-form.discharge-class',
                'exclude_choices' => $isPaho ? [DischargeClassification::SUSPECT]:[],
            ])
            ->add('dischClassOther', TextType::class, ['required' => false, 'label' => 'ibd-form.discharge-class-other', 'hidden' => ['parent'=> 'dischClass', 'value' => DischargeClassification::CONFIRMED_OTHER]])
            ->add('comment', null, ['required' => false, 'label' => 'ibd-form.comment']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meningitis::class,
        ]);
    }
}
