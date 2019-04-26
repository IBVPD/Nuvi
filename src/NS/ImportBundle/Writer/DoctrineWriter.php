<?php

namespace NS\ImportBundle\Writer;

use Ddeboer\DataImport\Writer\DoctrineWriter as BaseWriter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use NS\ImportBundle\Services\CamelCaser;

/**
 * Description of DoctrineWriter
 *
 * @author gnat
 */
class DoctrineWriter extends BaseWriter
{
    private $results;

    /**
     * Constructor
     *
     * @param ObjectManager $entityManager
     * @param string $entityName
     * @param string|array $lookupFields Index column or array of lookup fields to find current entities by
     */
    public function __construct(ObjectManager $entityManager, $entityName, $lookupFields = null)
    {
        parent::__construct($entityManager, $entityName, $lookupFields);

        $this->results = new ArrayCollection();
    }

    /**
     * @return array|Collection
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @return string
     */
    public function getEntityRepositoryMethod()
    {
        return $this->entityRepositoryMethod;
    }


    /**
     * @param array $item
     * @return mixed|null|object
     */
    protected function findOrCreateItem(array $item)
    {
        $res = parent::findOrCreateItem($item);
        $this->results->add($res);

        return $res;
    }

    /**
     * @param array $item
     * @param $entity
     */
    public function updateAssociations(array &$item, $entity)
    {
        parent::updateAssociations($item, $entity);

        if (isset($item['referenceLab'])) {
            $this->updateAssociation($item, $entity, 'referenceLab', 'NS\SentinelBundle\Entity\IBD\ReferenceLab');
        }

        if (isset($item['nationalLab'])) {
            $this->updateAssociation($item, $entity, 'nationalLab', 'NS\SentinelBundle\Entity\IBD\NationalLab');
        }
    }

    /**
     * @param array  $item
     * @param object $entity
     */
    protected function updateEntity(array $item, $entity)
    {
        $fieldNames = array_merge($this->entityMetadata->getFieldNames(), $this->entityMetadata->getAssociationNames());

        foreach ($fieldNames as $fieldName) {
            $value = null;
            if (isset($item[$fieldName])) {
                $value = $item[$fieldName];
            }

            if (null === $value) {
                continue;
            }

            if ($value !== $this->entityMetadata->getFieldValue($entity, $fieldName)) {
                $setter = 'set' .CamelCaser::process($fieldName);
                $this->setValue($entity, $value, $setter);
            }
        }
    }
}
