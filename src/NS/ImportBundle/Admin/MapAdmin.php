<?php

namespace NS\ImportBundle\Admin;

use NS\ImportBundle\Form\ClassType;
use NS\ImportBundle\Services\MapBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Entity\IBD;

/**
 * MapAdmin class
 */
class MapAdmin extends AbstractAdmin
{
    /** @var MapBuilder */
    private $mapBuilder;

    /**
     * @param MapBuilder $mapBuilder
     */
    public function setMapBuilder(MapBuilder $mapBuilder)
    {
        $this->mapBuilder = $mapBuilder;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('version')
            ->add('active')
            ->add('class', 'doctrine_orm_callback',
                [
                    'field_type' => 'choice',
                    'field_options' => [
                        'choices' => [
                            'Pneumonia' => Pneumonia::class,
                            'Meningitis' => Meningitis::class,
                            'RotaVirus' => RotaVirus::class,
                            'IBD' => IBD::class,
                        ],
                        'placeholder' => ' '
                    ],
                    'callback' => [$this, 'filterClassType']
                ]
            );
    }

    /**
     * @param $queryBuilder
     * @param $alias
     * @param $field
     * @param $value
     * @return bool|void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function filterClassType($queryBuilder, $alias, $field, $value)
    {
        if (!$value['value']) {
            return;
        }

        $queryBuilder
            ->andWhere(sprintf('%s.class = :class', $alias))
            ->setParameter('class', $value['value']);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('selectName')
            ->add('description', null, ['label' => 'notes'])
            ->add('active')
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'clone' => ['template' => 'NSImportBundle:MapAdmin:list__action_clone.html.twig'],
                ]
            ]);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $isNew = ($this->getSubject()->getId() > 0);

        $ibdRoute = $this->getRouteGenerator()->generate('exportFields', ['type' => 'ibd']);
        $pneuRoute = $this->getRouteGenerator()->generate('exportFields', ['type' => 'pneumonia']);
        $meningRoute = $this->getRouteGenerator()->generate('exportFields', ['type' => 'meningitis']);
        $rvRoute = $this->getRouteGenerator()->generate('exportFields', ['type' => 'rotavirus']);

        $help = sprintf('Field References:<br/>&nbsp;&nbsp;&nbsp;<a href="%s" target="_blank"><i class="fa fa-eye"></i> Pneumonia</a><br/>&nbsp;&nbsp;&nbsp;<a href="%s" target="_blank"><i class="fa fa-eye"></i> Meningitis</a><br/>&nbsp;&nbsp;&nbsp;<a href="%s" target="_blank"><i class="fa fa-eye"></i> IBD</a><br/>&nbsp;&nbsp;&nbsp;<a href="%s" target="_blank"><i class="fa fa-eye"></i> RV </a>', $pneuRoute, $meningRoute, $ibdRoute, $rvRoute);
        $formMapper
            ->add('name', null, ['label_attr' => ['class' => 'col-sm-2']])
            ->add('description', null, ['label' => 'Notes', 'label_attr' => ['class' => 'col-sm-2']])
            ->add('class', ClassType::class, ['label_attr' => ['class' => 'col-sm-2'], 'attr' => ['data-Class' => 'true'], 'help' => $help])
            ->add('version', null, ['required' => true, 'label_attr' => ['class' => 'col-sm-2']])
            ->add('headerRow', IntegerType::class, ['label_attr' => ['class' => 'col-sm-2']])
            ->add('caseLinker', ChoiceType::class, [
                'label_attr' => ['class' => 'col-sm-2'],
                'choices' => ['Case Id and Site Code' => 'ns_import.standard_case_linker', 'Case Id and Verify Country' => 'ns_import.reference_case_linker'],
                'placeholder' => 'Please Select...',
                'disabled' => $isNew]);

        if (!$isNew) {
            $formMapper
                ->add('labPreference', ChoiceType::class, [
                    'label_attr' => ['class' => 'col-sm-2'],
                    'choices' => ['RRL' => 'referenceLab', 'NL' => 'nationalLab']
                ])
                ->add('file', FileType::class, [
                    'required' => false,
                    'label_attr' => ['class' => 'col-sm-2'],
                ]);
        } else {
            $formMapper->add('columns', 'sonata_type_collection', ['error_bubbling' => false, 'by_reference' => true, 'attr' => ['class' => 'col-xs-12 col-sm-12 col-md-12'], 'label_attr' => ['class' => 'col-xs-12 col-sm-12 col-md-12 align-left']], ['edit' => 'inline', 'inline' => 'table']);
        }
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('active')
            ->add('version')
            ->add('columns');
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($map)
    {
        // have a file so build the columns dynamically
        if (!$map->getId() && $map->getFile()) {
            $metaData = $this->modelManager->getMetadata($map->getClass());
            $this->mapBuilder->setMetaData($metaData);
            $this->mapBuilder->setSiteMetaData($this->modelManager->getMetadata($metaData->getAssociationTargetClass('siteLab')));
            $this->mapBuilder->setNlMetaData($this->modelManager->getMetadata($metaData->getAssociationTargetClass('nationalLab')));

            $this->mapBuilder->process($map);
        } elseif ($map->getColumns()) {
            foreach ($map->getColumns() as $a) {
                $a->setMap($map);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($map)
    {
        if ($map->getColumns()) {
            foreach ($map->getColumns() as $a) {
                $a->setMap($map);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('clone', $this->getRouterIdParameter() . '/clone');
    }
}
