<?php

namespace NS\ImportBundle\Writer;

use \Ddeboer\DataImport\Writer\DoctrineWriter as BaseWriter;
use \Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\ORM\EntityManager;

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
     * @param EntityManager $entityManager
     * @param string        $entityName
     * @param string|array  $lookupFields Index column or array of lookup fields to find current entities by
     */
    public function __construct(EntityManager $entityManager, $entityName, $lookupFields = null)
    {
        parent::__construct($entityManager, $entityName, $lookupFields);

        $this->results = new ArrayCollection();
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
        $entity = parent::findOrCreateItem($item);
        $this->results->add($entity);

        return $entity;
    }
}