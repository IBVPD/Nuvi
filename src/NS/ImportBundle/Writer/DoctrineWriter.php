<?php

namespace NS\ImportBundle\Writer;

use Ddeboer\DataImport\Writer\DoctrineWriter as BaseWriter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Exception;

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
     * @param string        $index         Index to find current entities by
     */
    public function __construct(EntityManager $entityManager, $entityName, $index = null)
    {
        parent::__construct($entityManager, $entityName, $index);

        $this->results = new ArrayCollection();
    }

    protected function getNewInstance($className, array $item)
    {
        $cls = parent::getNewInstance($className, $item);

        $this->results->add($cls);

        return $cls;
    }

    public function getResults()
    {
        return $this->results;
    }
}
