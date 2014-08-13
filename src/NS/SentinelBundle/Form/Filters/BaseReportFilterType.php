<?php

namespace NS\SentinelBundle\Form\Filters;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Description of BaseFilter
 *
 * @author gnat
 */
class BaseReportFilterType extends AbstractType
{
    private $securityContext;

    public function __construct(SecurityContextInterface $sc)
    {
        $this->securityContext = $sc;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('createdAt','filter_date_range');

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
//                            if(count($objectIds) > 1)
//                                $form->add('region','region');

                            $form->add('country','country');
                            $form->add('site','site');
                        }

                        if($securityContext->isGranted('ROLE_COUNTRY'))
                        {
                            $objectIds = $user->getACLObjectIdsForRole('ROLE_COUNTRY');
//                            if(count($objectIds) > 1)
//                                $form->add('country','country');

                            $form->add('site','site');
                        }

                        if($securityContext->isGranted('ROLE_SITE'))
                        {
                            $objectIds = $user->getACLObjectIdsForRole('ROLE_SITE');
                            if(count($objectIds) > 1)
                                $form->add('site','site');
                        }

                        $form->add('filter','submit',array('icon' => 'icon-search','attr' => array('class'=>'btn btn-sm btn-success')))
                             ->add('export','submit',array('icon' => 'icon-cloud-download','attr' => array('class'=>'btn btn-sm btn-info')))
                             ->add('reset', 'submit',array('icon' => 'icon-times-circle','attr' => array('class'=>'btn btn-sm btn-danger')))
//                        $form->add('filter','submit',array('attr' => array('class'=>'btn btn-sm btn-success')))
//                             ->add('export','submit',array('attr' => array('class'=>'btn btn-sm btn-info')))
//                             ->add('reset', 'submit',array('attr' => array('class'=>'btn btn-sm btn-danger')))
                            ;
                    }
                    );
    }

    public function getName()
    {
        return 'BaseReportFilterType';
    }
}
