<?php

namespace NS\ImportBundle\Admin;

use \Sonata\AdminBundle\Admin\Admin;
use \Sonata\AdminBundle\Datagrid\DatagridMapper;
use \Sonata\AdminBundle\Datagrid\ListMapper;
use \Sonata\AdminBundle\Form\FormMapper;
use \Sonata\AdminBundle\Show\ShowMapper;

class ColumnAdmin extends Admin
{

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('order')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('order')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show'   => array(),
                    'edit'   => array(),
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
        $columns = ($this->getSubject() instanceof \NS\SentinelBundle\Entity\IBD)  ? 'ibd_columns':'rota_columns';
        $formMapper
            ->add('name')
            ->add('order',null,array('attr'=>array('class'=>'col-xs-3')))
            ->add('converter', 'ConverterChoice', array('required' => false,'attr'=>array('class'=>'chosen-select')))
            ->add('mapper',$columns)
            ->add('ignored', null, array('label' => 'Drop Field', 'required' => false))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('order')
            ->add('converter')
            ->add('mapper')
            ->add('ignored')
        ;
    }
}