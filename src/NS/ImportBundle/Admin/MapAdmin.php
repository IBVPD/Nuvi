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
use Symfony\Component\Form\AbstractType;
use Sonata\AdminBundle\Route\RouteCollection;

class MapAdmin extends Admin
{
    private $converterRegistry;

    public function setConverterRegistry(AbstractType $converterRegistry)
    {
        $this->converterRegistry = $converterRegistry;
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
            ->add('duplicateFields','tag', array('arrayOutput'=>true,'required'=>false))
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
        {
            $csvReader = new CsvReader($map->getFile()->openFile());
            $csvReader->setHeaderRowNumber(0);

            $headers     = $csvReader->getColumnHeaders();
            $columns     = array();
            $targetClass = $map->getClass();
            $target      = new $targetClass();
            $metaData    = $this->modelManager->getMetadata($map->getClass());

            foreach($headers as $index => $name)
            {
                $c = new Column();

                $c->setName($name);
                $c->setOrder($index);
                $c->setMap($map);

                $t = str_replace(array(' '),array(''),ucwords(str_replace(array('_','-'), array(' ',' '), strtolower($name))));
                $t[0] = strtolower($t[0]);
                $method = sprintf('get%s',$t);
                if(method_exists($target, $method))
                {
                    $c->setMapper($t);
                    try
                    {
                        $c->setConverter($this->converterRegistry->getConverterForField($metaData->getFieldMapping($t)));
                    }
                    catch(MappingException $e)
                    {
                    }
                }
                else
                    $c->setIsIgnored(true);

                $columns[] = $c;
            }
            $map->setColumns($columns);
        }
        else if($map->getColumns())
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

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('clone', $this->getRouterIdParameter().'/clone');
    }
}