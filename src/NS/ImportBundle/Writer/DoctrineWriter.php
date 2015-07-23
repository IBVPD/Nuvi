<?php

namespace NS\ImportBundle\Writer;

use \Ddeboer\DataImport\Writer\DoctrineWriter as BaseWriter;
use \Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\ORM\EntityManager;

/**
 * Description of DoctrineWriter
 *
 * @author gnat
 */
class DoctrineWriter extends BaseWriter
{

    private $results;
    private $loadAssociations = false;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     * @param string        $entityName
     * @param string|array  $lookupFields Index column or array of lookup fields to find current entities by
     */
    public function __construct(ObjectManager $entityManager, $entityName, $lookupFields = null)
    {
        parent::__construct($entityManager, $entityName, $lookupFields);

        $this->results = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function writeItem(array $item)
    {
        $this->counter++;
        $entity = $this->findOrCreateItem($item);

//        if ($this->getLoadAssociations()) {
            $this->loadAssociationObjectsToEntity($item, $entity);
//        }

        $fieldNames = array_merge($this->entityMetadata->getFieldNames(), $this->entityMetadata->getAssociationNames());
        foreach ($fieldNames as $fieldName) {

            $value = null;
            if (isset($item[$fieldName])) {
                $value = $item[$fieldName];
            } elseif (method_exists($item, 'get' . ucfirst($fieldName))) {
                $value = $item->{'get' . ucfirst($fieldName)};
            }

            if (null === $value) {
                continue;
            }

            if (!($value instanceof \DateTime)
                || $value != $this->entityMetadata->getFieldValue($entity, $fieldName)
            ) {
                $setter = 'set' . ucfirst($fieldName);
                $this->setValue($entity, $value, $setter);
            }
        }

        $this->entityManager->persist($entity);

//        if (($this->counter % $this->batchSize) == 0) {
//            $this->entityManager->flush();
//            $this->entityManager->clear($this->entityName);
//        }

        return $this;
    }

    public function setAssociationValue()
    {
        
    }

    /**
     * {@inheritdoc}
     */
    protected function getNewInstance()
    {
        $cls = parent::getNewInstance();

        $this->results->add($cls);

        return $cls;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * {@inheritdoc}
     */
    protected function findOrCreateItem(array $item)
    {
        $entity = null;
        // If the table was not truncated to begin with, find current entity
        // first
        if (false === $this->truncate) {
            if ($this->lookupFields) {
                $lookupConditions = array();
                foreach ($this->lookupFields as $fieldName) {
                    $lookupConditions[$fieldName] = $item[$fieldName];
                }

                if (method_exists($this->entityRepository, 'findWithRelations')) {
                    $entity = $this->entityRepository->findWithRelations($lookupConditions);
                }
                else {
                    $entity = $this->entityRepository->findOneBy(
                        $lookupConditions
                    );
                }
            }
            else {
                $entity = $this->entityRepository->find(current($item));
            }
        }

        if (!$entity) {
            return $this->getNewInstance();
        }

        $this->results->add($entity);

        return $entity;
    }

    public function getLoadAssociations()
    {
        return $this->loadAssociations;
    }

    public function setLoadAssociations($loadAssociations)
    {
        $this->loadAssociations = $loadAssociations;
        return $this;
    }
}
