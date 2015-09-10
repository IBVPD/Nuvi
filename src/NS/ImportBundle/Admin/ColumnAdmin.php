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
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
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
        $columns = null;
        if($this->getSubject()) {
            $columns = ($this->getSubject()->getMap()->getClass() == 'NS\SentinelBundle\Entity\IBD')  ? 'ibd_columns':'rota_columns';
        }

        $formMapper
            ->add('name',null,array('attr'=>array('data-queryBuilder'=>'columnName')))
            ->add('preProcessor', 'PreProcessorType',array('required'=>false))
            ->add('converter', 'ConverterChoice', array('required' => false,'attr'=>array('class'=>'chosen-select')))
            ->add('mapper',$columns,array('required'=>false))
            ->add('ignored', null, array('label' => 'Drop?', 'required' => false))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('converter')
            ->add('mapper')
            ->add('ignored')
        ;
    }
}