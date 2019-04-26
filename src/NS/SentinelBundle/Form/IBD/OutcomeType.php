<?php

namespace NS\SentinelBundle\Form\IBD;

use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeOutcome;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Description of OutcomeType
 *
 * @author gnat
 */
class OutcomeType extends AbstractType
{
    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    /**
     * OutcomeType constructor.
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isPaho = $this->authChecker->isGranted('ROLE_AMR');

        $builder
            ->add('dischOutcome', DischargeOutcome::class, ['required' => false, 'label' => 'ibd-form.discharge-outcome', 'exclude_choices' => $isPaho ? [DischargeOutcome::UNKNOWN]:[]])
            ->add('dischDx', DischargeDiagnosis::class, [
                'required' => false,
                'label' => 'ibd-form.discharge-diagnosis',
                'exclude_choices' => $isPaho ? [DischargeDiagnosis::UNKNOWN, DischargeDiagnosis::SEPSIS]:null
                ])
            ->add('dischDxOther', null, ['required' => false, 'label' => 'ibd-form.discharge-diagnosis-other', 'hidden' => ['parent' => 'dischDx', 'value' => DischargeDiagnosis::OTHER]])
            ->add('dischClass', DischargeClassification::class, [
                'required' => false,
                'label' => 'ibd-form.discharge-class',
                'exclude_choices' => $isPaho ? [DischargeClassification::UNKNOWN, DischargeClassification::SUSPECT] : null,
            ])
            ->add('comment', null, ['required' => false, 'label' => 'ibd-form.comment']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IBD::class,
        ]);
    }
}
