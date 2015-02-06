<?php

namespace NS\ImportBundle\Admin;

use Ddeboer\DataImport\Reader\CsvReader;
use Doctrine\ORM\Mapping\MappingException;
use NS\ImportBundle\Entity\Column;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class MapAdmin extends Admin
{
    private $mapBuilder;

    public function setMapBuilder($mapBuilder)
    {
        $this->mapBuilder = $mapBuilder;
        return $this;
    }

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
                    'clone' => array('template'=>'NSImportBundle:MapAdmin:list__action_clone.html.twig'),
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
            ->add('version');

        $model = $this->getSubject();
        if(!$model->getId())
            $formMapper->add('file','file',array('required'=>false));

        $formMapper->add('columns', 'sonata_type_collection', array('by_reference'=>true),array('edit'=>'inline','inline'=>'table'))
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
        if(!$map->getId() && $map->getFile()) // have a file so build the columns dynamically
            $map = $this->mapBuilder->process($map, $this->modelManager->getMetadata($map->getClass()));
        else if($map->getColumns())
        {
            foreach ($map->getColumns() as $a)
                $a->setMap($map);
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

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('clone', $this->getRouterIdParameter().'/clone');
    }
}