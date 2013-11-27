<?php

namespace NS\SentinelBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ACLAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('type','role')
            ->add('valid_from','datepicker',array('required'=>false))
            ->add('valid_to','datepicker',array('required'=>false))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('type')
            ->add('object_id')
            ->add('valid_from')
            ->add('valid_to')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('type','role')
            ->add('object_id','ns_autocomplete',array(
                                                        'label'               => 'Target',
                                                        'route'               => 'adminACLAjaxAutocomplete',
                                                        'class'               => '',
                                                        'collection'          => false,
                                                        'required'            => false,
                                                        'use_datatransformer' => true,
                                                        'secondary-field'     => array('s' => 'object_id','r' => 'type')))    
            ->add('valid_from','datepicker')
            ->add('valid_to','datepicker')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('user_id')
            ->add('object_id')
            ->add('valid_from')
            ->add('valid_to')
            ->add('type')
        ;
    }
}
