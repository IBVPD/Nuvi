<?php

namespace NS\ImportBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MapAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('version')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('class')
            ->add('version')
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
            ->add('name')
            ->add('class','ClassType')
            ->add('version')
            ->add('columns', 'sonata_type_collection', array('by_reference'=>true),array('edit'=>'inline','inline'=>'table'))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('version')
            ->add('columns')
        ;
    }


    public function prePersist($map)
    {
        if($map->getColumns())
        {
            foreach ($map->getColumns() as $a)
            {
                $a->setMap($map);
            }
        }

        return $map;
    }

    public function preUpdate($map)
    {
        if($map->getColumns())
        {
            foreach ($map->getColumns() as $a)
            {
                $a->setMap($map);
            }
        }

        return $map;
    }
}
