<?php

namespace NS\ImportBundle\Services;

use \Ddeboer\DataImport\Reader\CsvReader;
use \Ddeboer\DataImport\Workflow;
use \Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\DBAL\DBALException;
use \NS\ImportBundle\Entity\Import;
use \NS\ImportBundle\Filter\Duplicate;
use \NS\ImportBundle\Filter\NotBlank;
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
        $csvReader->setHeaderRowNumber(0); // should be tested by looking at the column names and value to see if the first row has headers

        // Create the workflow from the reader
        $workflow = new Workflow($csvReader);
        $workflow->setSkipItemOnFailure(true);

        $uniqueFields = array('getcode' => 'site', 'caseId');
        $duplicate    = new Duplicate($uniqueFields);

        // Create a writer: you need Doctrine’s EntityManager.
        $doctrineWriter = new DoctrineWriter($this->entityMgr, $map->getClass(), $uniqueFields);
        $doctrineWriter->setTruncate(false);
        $doctrineWriter->setBatchSize(2);

        $workflow->addWriter($doctrineWriter);

        foreach($map->getConverters() as $column)
        {
            $name = ($column->hasMapper())?$column->getMapper():$column->getName();
            $workflow->addValueConverter($name, $this->container->get($column->getConverter()));
        }

        $workflow->addItemConverter($map->getMappings());
        $workflow->addItemConverter($map->getIgnoredMapper());
        $workflow->addFilterAfterConversion(new NotBlank('caseId'));
        $workflow->addFilterAfterConversion($duplicate);

        try
        {
            // Process the workflow
            $processResult = $workflow->process();
        }
        catch (DBALException $ex)
        {
            $now = new \DateTime();
            return new Result("Error", $now, $now, 0, $duplicate, array($ex));
        }

        $result = new Result($processResult->getName(), $processResult->getStartTime(), $processResult->getEndTime(), $processResult->getTotalProcessedCount(), $duplicate, $processResult->getExceptions());
        $result->setResults($doctrineWriter->getResults());

        return $result;
    }
}
