<?php

namespace NS\ImportBundle\Admin;

use NS\ImportBundle\Converter\Registry;
use NS\ImportBundle\Form\Type\IBDColumnType;
use NS\ImportBundle\Form\Type\PreProcessorType;
use NS\ImportBundle\Form\Type\RotavirusColumnType;
use \Sonata\AdminBundle\Admin\Admin;
use \Sonata\AdminBundle\Datagrid\DatagridMapper;
use \Sonata\AdminBundle\Datagrid\ListMapper;
use \Sonata\AdminBundle\Form\FormMapper;
use \Sonata\AdminBundle\Show\ShowMapper;

class ColumnAdmin extends Admin
{
    /**
     * @param DatagridMapper $mapper
     */
    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper->add('name');
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
            ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $mapperType = null;
        if ($this->getSubject() && $this->getSubject()->getMap()) {
            $mapperType = ($this->getSubject()->getMap()->getClass() == 'NS\SentinelBundle\Entity\IBD') ? IBDColumnType::class : RotavirusColumnType::class;
        }

        $formMapper
            ->add('name', null, array('attr' => array('data-queryBuilder' => 'columnName')))
            ->add('preProcessor', PreProcessorType::class, array('required' => false))
            ->add('mapper',         $mapperType, array('required'=>false, 'label' => 'DB Column'))
            ->add('converter', Registry::class, array('required' => false, 'attr' => array('class' => 'chosen-select'), 'label' => 'Validator'))
            ->add('ignored', null, array('label' => 'Drop?', 'required' => false));
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
            ->add('ignored');
    }
}
