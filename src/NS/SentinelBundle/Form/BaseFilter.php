<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Security\Core\SecurityContext;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Doctrine\Common\Persistence\ObjectManager;
use \Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;

class BaseFilter extends AbstractType
{
    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'filter_text', array(
                                            'required'          => false,
                                            'condition_pattern' => FilterOperands::STRING_BOTH,
                                            'label'             => 'db-generated-id'));

        $builder->add('caseId', 'filter_text', array(
                                            'required'          => false,
                                            'condition_pattern' => FilterOperands::STRING_BOTH,
                                            'label'             => 'site-assigned-case-id'));

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

            if($this->securityContext->isGranted('ROLE_CAN_CREATE_NL_LAB'))
            {
                $builder->add('isComplete',null,array('required'=>false, 'label' => 'filter-case-is-complete'));
            }

            if($this->securityContext->isGranted('ROLE_CAN_CREATE_RRL_LAB'))
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
            'data_class'      => 'NS\SentinelBundle\Filter\Meningitis',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'base_filter_form';
    }
}
