<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Security\Core\SecurityContext;
use NS\SecurityBundle\Doctrine\SecuredQuery;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Doctrine\Common\Persistence\ObjectManager;
use \Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use NS\SentinelBundle\Entity\User;

class MeningitisFilter extends AbstractType
{
    private $securityContext;

    private $entityManager;

    public function __construct(SecurityContext $securityContext, ObjectManager $entityManager)
    {
        $this->securityContext = $securityContext;
        $this->entityManager   = $entityManager;
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
                                            'label'             => 'Case Id'));

        $securityContext = $this->securityContext;
        $entityManager   = $this->entityManager;

        $builder->addEventListener(
                    FormEvents::PRE_SET_DATA,
                    function(FormEvent $event) use($securityContext,$entityManager)
                    {
                        $form = $event->getForm();
                        $user = $securityContext->getToken()->getUser();

                        if($securityContext->isGranted('ROLE_REGION'))
                        {
                            $objectIds = $user->getACLObjectIdsForRole('ROLE_REGION');
                            if(count($objectIds) > 1)
                            {
                                $qb = $entityManager->getRepository('NSSentinelBundle:Region')->getAllSecuredQueryBuilder();
                                $form->add('country','filter_entity',array('class'           => 'NSSentinelBundle:Region',
                                                                           'multiple'      => true,
                                                                           'query_builder' => $qb));

                            }

                            $qb = $entityManager->getRepository('NSSentinelBundle:Country')->getAllSecuredQueryBuilder();
                            $form->add('country','filter_entity',array('class'           => 'NSSentinelBundle:Country',
                                                                         'multiple'      => true,
                                                                         'query_builder' => $qb));
                            $qb = $entityManager->getRepository('NSSentinelBundle:Site')->getAllSecuredQueryBuilder();
                            $form->add('site','filter_entity',  array('class'         => 'NSSentinelBundle:Site',
                                                                      'multiple'      => true,
                                                                      'query_builder' => $qb));
                        }

                        if($securityContext->isGranted('ROLE_COUNTRY'))
                        {
                            $objectIds = $user->getACLObjectIdsForRole('ROLE_COUNTRY');
                            if(count($objectIds) > 1)
                            {
                                $qb = $entityManager->getRepository('NSSentinelBundle:Country')->getAllSecuredQueryBuilder();
                                $form->add('country','filter_entity',array('class'           => 'NSSentinelBundle:Country',
                                                                             'multiple'      => true,
                                                                             'query_builder' => $qb));
                            }

                            $qb = $entityManager->getRepository('NSSentinelBundle:Site')->getAllSecuredQueryBuilder();
                            $form->add('site','filter_entity',  array('class'         => 'NSSentinelBundle:Site',
                                                                      'multiple'      => true,
                                                                      'query_builder' => $qb));
                        }

                        if($securityContext->isGranted('ROLE_SITE'))
                        {
                            $objectIds = $user->getACLObjectIdsForRole('ROLE_SITE');
                            if(count($objectIds) > 1)
                            {
                                $qb = $entityManager->getRepository('NSSentinelBundle:Site')->getAllSecuredQueryBuilder();
                                $form->add('site','filter_entity',  array('class'         => 'NSSentinelBundle:Site',
                                                                          'multiple'      => true,
                                                                          'query_builder' => $qb));
                            }
                        }

                        $form->add('find','iconbutton',array('type'=>'submit', 'icon' => 'icon-search','attr'=>array('class'=>'btn btn-sm btn-success')));
                    }
                    );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'NS\SentinelBundle\Entity\MeningitisFilter',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'meningitis_filter_form';
    }
}
