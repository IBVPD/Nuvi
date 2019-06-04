<?php

namespace NS\SentinelBundle\Filter\Type;

use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use NS\SecurityBundle\Role\ACLConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BaseFilterType extends AbstractType
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    /** @var ACLConverter */
    private $aclConverter;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker, ACLConverter $aclConverter)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker  = $authChecker;
        $this->aclConverter = $aclConverter;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('case_id', TextFilterType::class, [
                'required'          => false,
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
                'label'             => 'site-assigned-case-id'
            ])
            ->add('adm_date', DateRangeFilterType::class, [
                'required' => false,
                'label'    => 'filter.admission-date',
                'left_date_options' => ['label' => 'Admission Date - From'],
                'right_date_options' => ['label' => 'Admission Date - To'],
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'preSetData']);
    }

    public function preSetData(FormEvent $event): void
    {
        $form  = $event->getForm();
        $token = $this->tokenStorage->getToken();

        if ($this->authChecker->isGranted('ROLE_REGION')) {
            $objectIds = $this->aclConverter->getObjectIdsForRole($token, 'ROLE_REGION');
            if (count($objectIds) > 1) {
                $form->add('region', RegionType::class);
            }

            $form
                ->add('id', TextFilterType::class, [
                    'required'          => false,
                    'condition_pattern' => FilterOperands::STRING_CONTAINS,
                    'label'             => 'db-generated-id']
                )
                ->add('country', CountryType::class, ['required' => false, 'placeholder' => ''])
                ->add('site', SiteType::class);
        }

        if ($this->authChecker->isGranted('ROLE_COUNTRY')) {
            $objectIds = $this->aclConverter->getObjectIdsForRole($token, 'ROLE_COUNTRY');
            if (count($objectIds) > 1) {
                $form->add('country', CountryType::class, ['required'=>false, 'placeholder'=>'']);
            }

            $form
                ->add('firstName', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS])
                ->add('lastName', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS])
                ->add('site', SiteType::class);
        }

        if ($this->authChecker->isGranted('ROLE_SITE')) {
            $objectIds = $this->aclConverter->getObjectIdsForRole($token, 'ROLE_SITE');
            if (count($objectIds) > 1) {
                $form->add('site', SiteType::class);
            }

            $form
                ->add('firstName', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS])
                ->add('lastName', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS]);

        }

        $form->add('find', SubmitType::class, [
            'label'=> 'find',
            'type' => 'submit',
            'icon' => 'fa fa-search',
            'attr' => ['class' => 'btn btn-xs btn-success pull-right']]);

        $form->add('reset', SubmitType::class, [
            'label'=>'reset',
            'type' => 'reset',
            'icon' => 'fa fa-times-circle',
            'attr' => ['class' => 'btn btn-xs btn-danger', 'type'=>'submit']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
        ]);
    }
}
