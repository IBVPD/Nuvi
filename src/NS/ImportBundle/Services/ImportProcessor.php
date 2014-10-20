<?php

namespace NS\ImportBundle\Services;

use \Ddeboer\DataImport\Reader\CsvReader;
use \Ddeboer\DataImport\Workflow;
use \Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\DBAL\DBALException;
use \NS\ImportBundle\Entity\Import;
use \NS\ImportBundle\Filter\Duplicate;
use \NS\ImportBundle\Filter\Unique;
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
    private $entityMgr;
    private $container;

    public function __construct(ObjectManager $entityMgr, ContainerInterface $container)
    {
        $this->entityMgr = $entityMgr;
        $this->container = $container;
    }

    /**
     *
     * @param Import $import
     * @return Result
     */
    public function process(Import $import)
    {
        ini_set('max_execution_time', 90);
        ini_set('memory_limit', '512M');

        $map = $import->getMap();

        // Create and configure the reader
        $csvReader = new CsvReader($import->getFile()->openFile(),',');

        // Tell the reader that the first row in the CSV file contains column headers
        $csvReader->setHeaderRowNumber(0);

        // Create the workflow from the reader
        $workflow = new Workflow($csvReader);
        $workflow->setSkipItemOnFailure(true);

        // Create a writer: you need Doctrine’s EntityManager.
        $doctrineWriter = new DoctrineWriter($this->entityMgr, $map->getClass(), $map->getFindBy());
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
        {
            $workflow->addFilter(new Duplicate($map->getDuplicateFields()));

            if(!$map->getFindBy()) // FindBy is basically the id which means we don't care if the record exists as it'll be loaded anyway
                $workflow->addFilterAfterConversion(new Unique($this->entityMgr->getRepository($map->getClass()), $map->getMappedDuplicateFields()));
        }

        try
        {
            // Process the workflow
            $processResult = $workflow->process();
        }
        catch (DBALException $ex)
        {
            return new Result("Error", new \DateTime(), new \DateTime(), 0, array($ex));
        }

        $result = new Result($processResult->getName(), $processResult->getStartTime(), $processResult->getEndTime(), $processResult->getTotalProcessedCount(), $processResult->getExceptions());
        $result->setResults($doctrineWriter->getResults());

        return $result;
    }
}
