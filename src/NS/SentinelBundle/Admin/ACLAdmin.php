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
            ->add('type', 'role')
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
            ->add('type', 'role')
            ->add('options', 'choice', array(
                'required' => false,
                'multiple' => true,
                'choices' => array(
                    'api' => 'Api Access',
                    'import' => 'Import/Export Access',
                    'case' => 'Can Create Case',
                    'lab' => 'Can Create Site Lab',
                    'nl' => 'Can Create NL',
                    'rrl' => 'Can Create RRL'))
            )
            ->add('object_id', 'acl_object_target_autocompleter', array(
                                                        'label'               => 'Target',
                                                        'route'               => 'adminACLAjaxAutocomplete',
                                                        'required'            => false,
                                                        'secondary-field'     => array('s' => 'object_id', 'r' => 'type')))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('user')
            ->add('object_id')
            ->add('options')
            ->add('type')
        ;
    }
}
