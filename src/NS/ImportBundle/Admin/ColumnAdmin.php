<?php

namespace NS\ImportBundle\Admin;

use NS\ImportBundle\Converter\Registry;
use NS\ImportBundle\Form\Type\IBDColumnType;
use NS\ImportBundle\Form\Type\MeningitisColumnType;
use NS\ImportBundle\Form\Type\PneumoniaColumnType;
use NS\ImportBundle\Form\Type\PreProcessorType;
use NS\ImportBundle\Form\Type\RotavirusColumnType;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\RotaVirus;
use RuntimeException;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use function uniqid;

class ColumnAdmin extends AbstractAdmin
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
            ->add('_action', 'actions', [
                'actions' => [
                    'show'   => [],
                    'edit'   => [],
                    'delete' => [],
                ]
            ]);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $mapperType = null;
        if ($this->getSubject() && $this->getSubject()->getMap()) {
            $mapperType = null;
            switch($this->getSubject()->getMap()->getClass()) {
                case IBD::class:
                    $mapperType = IBDColumnType::class;
                    break;
                case Pneumonia::class:
                    $mapperType = PneumoniaColumnType::class;
                    break;
                case Meningitis::class:
                    $mapperType = MeningitisColumnType::class;
                    break;
                case RotaVirus::class:
                    $mapperType = RotavirusColumnType::class;
                    break;
            }

            if ($mapperType === null) {
                throw new RuntimeException('Unable to determine column mapper type');
            }
        }

        $id = uniqid('columnId', true);
        $formMapper
            ->add('name', null, ['attr' => ['data-queryBuilder' => 'columnName']])
            ->add('preProcessor', PreProcessorType::class, ['required' => false])
            ->add('mapper', $mapperType, ['required' => false, 'label' => 'DB Column', 'attr' => ['data-dbcolumn' => true, 'data-ref' => $id, 'class'=>'mapperSelect']])
            ->add('converter',
                Registry::class,
                [
                    'required' => false,
                    'label' => 'Validator',
                    'attr' => [
                        'data-converter' => true, 'data-ref' => $id, 'class'=>'mapperSelect'
                    ]
                ])
            ->add('ignored', null, ['label' => 'Drop?', 'required' => false]);
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
