<?php

namespace NS\ImportBundle\Services;

use \Ddeboer\DataImport\Reader\CsvReader;
use \Ddeboer\DataImport\Workflow;
use \Doctrine\Common\Persistence\ObjectManager;
use \NS\ImportBundle\Entity\Import;
use \NS\ImportBundle\Filter\Duplicate;
use \NS\ImportBundle\Writer\DoctrineWriter;
use \NS\ImportBundle\Writer\Result;
use \Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of ImportProcessor
 *
 * @author gnat
 */
class ImportProcessor
{
    private $em;
    private $container;

    public function __construct(ObjectManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     *
     * @param \NS\ImportBundle\Entity\Import $import
     * @return \NS\ImportBundle\Writer\Result
     */
    public function process(Import $import)
    {
        ini_set('max_execution_time', 90);

        $map = $import->getMap();

        // Create and configure the reader
        $csvReader = new CsvReader($import->getFile()->openFile(),',');

        // Tell the reader that the first row in the CSV file contains column headers
        $csvReader->setHeaderRowNumber(0);

        // Create the workflow from the reader
        $workflow = new Workflow($csvReader);
        $workflow->setSkipItemOnFailure(true);

        // Create a writer: you need Doctrine’s EntityManager.
        $doctrineWriter = new DoctrineWriter($this->em, $map->getClass(), $map->getFindBy());
        $doctrineWriter->setTruncate(false);

        $workflow->addWriter($doctrineWriter);

        foreach($map->getConverters() as $column)
        {
            $name = ($column->hasMapper())?$column->getMapper():$column->getName();
            $workflow->addValueConverter($name, $this->container->get($column->getConverter()));
        }

        $workflow->addItemConverter($map->getMappings());
        $workflow->addItemConverter($map->getIgnoredMapper());

        if($map->getDuplicateFields())
            $workflow->addFilter(new Duplicate($map->getDuplicateFields()));

        try
        {
            // Process the workflow
            $c = $workflow->process();
        }
        catch (\Doctrine\DBAL\DBALException $ex)
        {
            return new Result("Error", new \DateTime, new \DateTime, 0, array($ex));
        }

        $result = new Result($c->getName(), $c->getStartTime(), $c->getEndTime(), $c->getTotalProcessedCount(), $c->getExceptions());
        $result->setResults($doctrineWriter->getResults());

        return $result;
    }
}
