<?php

namespace NS\ImportBundle\Writer;

use Ddeboer\DataImport\Writer\DoctrineWriter as BaseWriter;

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

        $this->results = new \Doctrine\Common\Collections\ArrayCollection();
    }

    protected function getNewInstance($className, array $item)
    {
        if (class_exists($className) === false) {
            throw new \Exception('Unable to create new instance of ' . $className);
        }

        $cls = new $className;

        $this->results->add($cls);

        return $cls;
    }

    public function getResults()
    {
        return $this->results;
    }
}
