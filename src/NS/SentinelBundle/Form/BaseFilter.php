<?php

namespace NS\SentinelBundle\Form;

use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

class BaseFilter extends AbstractType
{
    private $securityContext;

    /**
     * @param SecurityContext $securityContext
     */
    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('caseId', 'filter_text', array(
                                            'required'          => false,
                                            'condition_pattern' => FilterOperands::STRING_BOTH,
                                            'label'             => 'site-assigned-case-id'));
        $builder->add('admDate', 'filter_date_range', array(
                                            'required'          => false,
                                            'label'             => 'filter.admission-date'));

        $securityContext = $this->securityContext;

        $builder->addEventListener(
                    FormEvents::PRE_SET_DATA,
                    function(FormEvent $event) use($securityContext)
                    {
                        $form = $event->getForm();
                        $user = $securityContext->getToken()->getUser();

                        if($securityContext->isGranted('ROLE_REGION'))
                        {
                            $objectIds = $user->getACLObjectIdsForRole('ROLE_REGION');
                            if(count($objectIds) > 1)
                                $form->add('region','region');

                            $form->add('id', 'filter_text', array(
                                                                    'required'          => false,
                                                                    'condition_pattern' => FilterOperands::STRING_BOTH,
                                                                    'label'             => 'db-generated-id'));
                            $form->add('country','country');
                            $form->add('site','site');
                        }

                        if($securityContext->isGranted('ROLE_COUNTRY'))
                        {
                            $objectIds = $user->getACLObjectIdsForRole('ROLE_COUNTRY');
                            if(count($objectIds) > 1)
                                $form->add('country','country');

                            $form->add('site','site');
                        }

                        if($securityContext->isGranted('ROLE_SITE'))
                        {
                            $objectIds = $user->getACLObjectIdsForRole('ROLE_SITE');
                            if(count($objectIds) > 1)
                                $form->add('site','site');
                        }

                        $form->add('find','iconbutton',array('type' => 'submit', 'icon' => 'icon-search','attr' => array('class'=>'btn btn-sm btn-success')));
                    }
                    );
/*
        if($this->securityContext->isGranted('ROLE_CAN_CREATE'))
        {
            if($this->securityContext->isGranted('ROLE_CAN_CREATE_LAB'))
            {
                $builder->add('isComplete',null,array('required'=>false, 'label' => 'filter-case-is-complete'));
            }
        }
 */
     }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'method'      => 'GET',
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
