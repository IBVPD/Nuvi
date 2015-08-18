<?php

namespace NS\ImportBundle\Writer;

use \Ddeboer\DataImport\Writer\DoctrineWriter as BaseWriter;
use \Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\Persistence\ObjectManager;

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
     * @return array
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
}
