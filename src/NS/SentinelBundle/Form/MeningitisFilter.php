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

class MeningitisFilter extends AbstractType
{
    private $securityContext;

    private $securedQuery;

    private $entityManager;

    public function __construct(SecurityContext $securityContext, SecuredQuery $securedQuery, ObjectManager $entityManager)
    {
        $this->securityContext = $securityContext;
        $this->securedQuery    = $securedQuery;
        $this->entityManager   = $entityManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id',     'filter_text',array('required'=>false,'condition_pattern'=>  \Lexik\Bundle\FormFilterBundle\Filter\FilterOperands::STRING_STARTS))
//                ->add('site',   'filter_entity',array('required'=>false))
//                ->add('country','filter_entity',array('required'=>false))
//                ->add('region', 'filter_entity',array('required'=>false))
                ;

        $securityContext = $this->securityContext;
        $securedQuery    = $this->securedQuery;
        $entityManager   = $this->entityManager;

        $builder->addEventListener(
                    FormEvents::PRE_SET_DATA,
                    function(FormEvent $event) use($securityContext,$securedQuery,$entityManager)
                    {
                        $form = $event->getForm();

                        if($securityContext->isGranted('ROLE_REGION'))
                        {
                            $qb = $entityManager->getRepository('NSSentinelBundle:Country')->createQueryBuilder('c')->select('s')->from('NSSentinelBundle:Country','s');
                            $form->add('country','filter_entity',array('class'           => 'NSSentinelBundle:Country',
                                                                         'multiple'      => true,
                                                                         'query_builder' => $securedQuery->secure($qb)));
                            $qb = $entityManager->getRepository('NSSentinelBundle:Site')->createQueryBuilder('c')->select('s')->from('NSSentinelBundle:Site','s');
                            $form->add('site','filter_entity',  array('class'         => 'NSSentinelBundle:Site',
                                                                      'multiple'      => true,
                                                                      'query_builder' => $securedQuery->secure($qb)));

                        }

                        if($securityContext->isGranted('ROLE_COUNTRY'))
                        {
                            $qb = $entityManager->getRepository('NSSentinelBundle:Site')->createQueryBuilder('c')->select('s')->from('NSSentinelBundle:Site','s');
                            $form->add('site','filter_entity',array('class'         => 'NSSentinelBundle:Site',
                                                                    'multiple'      => true,
                                                                    'query_builder' => $securedQuery->secure($qb)));
                        }

                        if($securityContext->isGranted('ROLE_SITE'))
                        {
                            $qb = $entityManager->getRepository('NSSentinelBundle:Site')->createQueryBuilder('c')->select('s')->from('NSSentinelBundle:Site','s');
                            $form->add('site','filter_entity',  array('class'         => 'NSSentinelBundle:Site',
                                                                      'multiple'      => true,
                                                                      'query_builder' => $securedQuery->secure($qb)));
                        }

                        $form->add('find','submit',array('attr'=>array('class'=>'filter','label'=>'Find')));
                    }
                    );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\MeningitisFilter'
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
