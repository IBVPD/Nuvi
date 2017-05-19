<?php

namespace NS\SentinelBundle\Filter\Type;

use NS\AceBundle\Filter\Type\DateRangeFilterType;
use NS\SecurityBundle\Role\ACLConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Description of BaseReportFilterType
 *
 * @author gnat
 */
class BaseReportFilterType extends AbstractType
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var ACLConverter
     */
    private $converter;

    /**
     * BaseReportFilterType constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authChecker
     * @param ACLConverter $converter
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker, ACLConverter $converter)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker  = $authChecker;
        $this->converter    = $converter;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adm_date', DateRangeFilterType::class, [
                'label' => 'report-filter-form.admitted-between',
                'left_date_options' => ['label' => 'Admission Date - From'],
                'right_date_options' => ['label' => 'Admission Date - To'],
            ])
            ->add('createdAt', DateRangeFilterType::class, [
                'label' => 'report-filter-form.created-between',
                'left_date_options' => ['label' => 'Record Creation Date - From'],
                'right_date_options' => ['label' => 'Record Creation Date - To'],
            ])
            ->add('exportFormat', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'label'=> 'Export as Excel (may have row limit issues)',
                'apply_filter' => function () {}, // empty because this never applies a filter
            ])
        ;

        if ($options['include_paho_format_option']) {
            $builder->add('pahoFormat', CheckboxType::class, ['label' => 'Use AMRO/PAHO format?', 'required' => false, 'mapped' => false, 'apply_filter' => function () {}]);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'preSetData']);
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form     = $event->getForm();
        $options  = $form->getConfig()->getOptions();
        $siteType = (isset($options['site_type']) && $options['site_type'] == 'advanced') ? SiteFilterType::class : SiteType::class;

        if ($this->authChecker->isGranted('ROLE_REGION')) {
            $objectIds = $this->converter->getObjectIdsForRole($this->tokenStorage->getToken(), 'ROLE_REGION');
            if (count($objectIds) > 1) {
                $form->add('region', RegionType::class);
            }

            $form->add('country', CountryType::class, ['placeholder' => '', 'required' => false]);
            $form->add('site', $siteType);
        } elseif ($this->authChecker->isGranted('ROLE_COUNTRY')) {
            $form->add('site', $siteType);
        } elseif ($this->authChecker->isGranted('ROLE_SITE')) {
            $token     = $this->tokenStorage->getToken();
            $objectIds = $this->converter->getObjectIdsForRole($token, 'ROLE_SITE');
            if (count($objectIds) > 1) {
                $form->add('site', $siteType);
            }
        }

        if ($options['include_filter']) {
            $form->add('filter', SubmitType::class, [
                'label'=>'filter',
                'icon' => 'fa fa-search',
                'attr' => ['class' => 'btn btn-xs btn-success']]);
        }

        if ($options['include_export']) {
            $form->add('export', SubmitType::class, [
                'label' => 'export',
                'icon' => 'fa fa-cloud-download',
                'attr' => ['class' => 'btn btn-xs btn-info']]);
        }

        if ($options['include_reset']) {
            $form->add('reset', SubmitType::class, [
                'label' => 'reset',
                'icon' => 'fa fa-times-circle',
                'attr' => ['class' => 'btn btn-xs btn-danger']]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'include_filter' => true,
            'include_export' => true,
            'include_reset'  => true,
            'include_paho_format_option' => false,
            ]
        );

        $resolver->setDefined(['site_type']);
        $resolver->setAllowedValues('site_type', ['simple', 'advanced']);
        $resolver->setRequired('data_class');
    }
}
