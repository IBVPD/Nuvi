<?php

namespace NS\SentinelBundle\Filter\Type;

use \NS\SecurityBundle\Role\ACLConverter;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
            ->add('admDate', 'ns_filter_date_range', array('label' => 'report-filter-form.admitted-between',))
            ->add('createdAt', 'ns_filter_date_range', array('label' => 'report-filter-form.created-between'));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this,'preSetData'));
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form     = $event->getForm();
        $options  = $form->getConfig()->getOptions();
        $siteType = ( isset($options['site_type']) && $options['site_type'] == 'advanced') ? new SiteFilterType() : 'site';

        if ($this->authChecker->isGranted('ROLE_REGION')) {
            $form->add('country', 'country',array('placeholder' => '','required' => false));
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
            $form->add('filter', 'submit', array(
                'icon' => 'fa fa-search',
                'attr' => array('class' => 'btn btn-sm btn-success')));
        }

        if ($options['include_export']) {
            $form->add('export', 'submit', array(
                'icon' => 'fa fa-cloud-download',
                'attr' => array('class' => 'btn btn-sm btn-info')));
        }

        if ($options['include_reset']) {
            $form->add('reset', 'submit', array(
                'icon' => 'fa fa-times-circle',
                'attr' => array('class' => 'btn btn-sm btn-danger')));
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'include_filter' => true,
            'include_export' => true,
            'include_reset'  => true)
        );

        $resolver->setDefined(array('site_type'));
        $resolver->setAllowedValues('site_type', array('simple', 'advanced'));
        $resolver->setRequired('data_class');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'BaseReportFilterType';
    }
}
