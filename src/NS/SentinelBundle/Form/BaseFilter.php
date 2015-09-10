<?php

namespace NS\SentinelBundle\Form;

use \Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Security\Core\SecurityContextInterface;

class BaseFilter extends AbstractType
{
    private $securityContext;

    private $aclConverter;

    /**
     * @param SecurityContextInterface $securityContext
     * @param ACLConverter $aclConverter
     */
    public function __construct(SecurityContextInterface $securityContext, \NS\SecurityBundle\Role\ACLConverter $aclConverter)
    {
        $this->securityContext = $securityContext;
        $this->aclConverter    = $aclConverter;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('caseId', 'filter_text', array(
                'required'          => false,
                'condition_pattern' => FilterOperands::STRING_BOTH,
                'label'             => 'site-assigned-case-id'
            ))
            ->add('admDate', 'filter_date_range', array(
                'required' => false,
                'label'    => 'filter.admission-date'
            ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'preSetData'));
    }

    public function preSetData(FormEvent $event)
    {
        $form  = $event->getForm();
        $token = $this->securityContext->getToken();

        if ($this->securityContext->isGranted('ROLE_REGION'))
        {
            $objectIds = $this->aclConverter->getObjectIdsForRole($token, 'ROLE_REGION');
            if (count($objectIds) > 1) {
                $form->add('region', 'region');
            }

            $form->add('id', 'filter_text', array(
                'required'          => false,
                'condition_pattern' => FilterOperands::STRING_BOTH,
                'label'             => 'db-generated-id'));
            $form->add('country', 'country');
            $form->add('site', 'site');
        }

        if ($this->securityContext->isGranted('ROLE_COUNTRY')) {
            $objectIds = $this->aclConverter->getObjectIdsForRole($token, 'ROLE_COUNTRY');
            if (count($objectIds) > 1) {
                $form->add('country', 'country');
            }

            $form->add('site', 'site');
        }

        if ($this->securityContext->isGranted('ROLE_SITE'))
        {
            $objectIds = $this->aclConverter->getObjectIdsForRole($token, 'ROLE_SITE');
            if (count($objectIds) > 1) {
                $form->add('site', 'site');
            }
        }

        $form->add('find', 'iconbutton', array('type' => 'submit', 'icon' => 'fa fa-search',
            'attr' => array('class' => 'btn btn-sm btn-success')));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'GET',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'base_filter_form';
    }
}
