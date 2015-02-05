<?php

namespace NS\SentinelBundle\Form\Filters;

use \Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use \NS\SecurityBundle\Role\ACLConverter;
use \NS\SentinelBundle\Form\Filters\SiteFilterType;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Description of BaseFilter
 *
 * @author gnat
 */
class BaseReportFilterType extends AbstractType
{
    private $securityContext;

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
        $choices = array(1 => 'Yes', 0 => 'No');

        $builder->add('admDate', 'ns_filter_date_range', array('label' => 'report-filter-form.admitted-between',))
            ->add('createdAt', 'ns_filter_date_range', array('label' => 'report-filter-form.created-between'))
        ;

        $securityContext = $this->securityContext;
        $converter       = $this->converter;
        $siteType        = ( isset($options['site_type']) && $options['site_type'] == 'advanced') ? new SiteFilterType() : 'site';

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA, function(FormEvent $event) use($securityContext, $converter, $siteType, $options) {
            $form  = $event->getForm();
            $token = $securityContext->getToken();

            if ($securityContext->isGranted('ROLE_REGION'))
            {
                $objectIds = $converter->getObjectIdsForRole($token, 'ROLE_REGION');
                $form->add('country', 'country');
                $form->add('site', $siteType);
            }
            else if ($securityContext->isGranted('ROLE_COUNTRY'))
            {
                $objectIds = $converter->getObjectIdsForRole($token, 'ROLE_COUNTRY');
                $form->add('site', $siteType);
            }
            else if ($securityContext->isGranted('ROLE_SITE'))
            {
                $objectIds = $converter->getObjectIdsForRole($token, 'ROLE_SITE');
                if (count($objectIds) > 1)
                    $form->add('site', $siteType);
            }

            if ($options['include_filter'])
                $form->add('filter', 'submit', array(
                    'icon' => 'icon-search',
                    'attr' => array('class' => 'btn btn-sm btn-success')));
            if ($options['include_export'])
                $form->add('export', 'submit', array(
                    'icon' => 'icon-cloud-download',
                    'attr' => array('class' => 'btn btn-sm btn-info')));
            if ($options['include_reset'])
                $form->add('reset', 'submit', array(
                    'icon' => 'icon-times-circle',
                    'attr' => array('class' => 'btn btn-sm btn-danger')));
        }
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'include_filter' => true,
            'include_export' => true,
            'include_reset'  => true)
        );

        $resolver->setOptional(array('site_type'));
        $resolver->setAllowedValues(array('site_type' => array('simple', 'advanced')));
    }

    public function getName()
    {
        return 'BaseReportFilterType';
    }
}