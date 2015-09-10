<?php

namespace NS\SentinelBundle\Form\Filters;

use \NS\SecurityBundle\Role\ACLConverter;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Description of BaseFilter
 *
 * @author gnat
 */
class BaseReportFilterType extends AbstractType
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var ACLConverter
     */
    private $converter;

    /**
     * @param SecurityContextInterface $securityContext
     * @param ACLConverter $converter
     */
    public function __construct(SecurityContextInterface $securityContext, ACLConverter $converter)
    {
        $this->securityContext = $securityContext;
        $this->converter       = $converter;
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

        if ($this->securityContext->isGranted('ROLE_REGION')) {
            $form->add('country', 'country');
            $form->add('site', $siteType);
        } elseif ($this->securityContext->isGranted('ROLE_COUNTRY')) {
            $form->add('site', $siteType);
        } elseif ($this->securityContext->isGranted('ROLE_SITE')) {
            $token     = $this->securityContext->getToken();
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
        $resolver->setAllowedValues(array('site_type' => array('simple', 'advanced')));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'BaseReportFilterType';
    }
}
