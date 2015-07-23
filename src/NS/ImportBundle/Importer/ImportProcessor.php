<?php

namespace NS\ImportBundle\Importer;

use \Ddeboer\DataImport\Reader\CsvReader;
use \Ddeboer\DataImport\Workflow;
use \Ddeboer\DataImport\Reader\ReaderInterface;
use \Ddeboer\DataImport\Step\FilterStep;
use \Ddeboer\DataImport\Step\ValueConverterStep;
use \Doctrine\DBAL\DBALException;
use \NS\ImportBundle\Entity\Result;
use \NS\ImportBundle\Filter\Duplicate;
use \NS\ImportBundle\Filter\NotBlank;
use \NS\ImportBundle\Writer\DoctrineWriter;
use \Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of ImportProcessor
 *
 * @author gnat
 */
class ImportProcessor
{
    private $container;
    private $duplicateFilter;
    private $notBlankFilter;
    private $memoryLimit = '1024M';
    private $maxExecutionTime = 190;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * @param Result $import
     * @return Result
     */
    public function process(Result $import)
    {
        ini_set('max_execution_time', $this->maxExecutionTime);
        ini_set('memory_limit', $this->memoryLimit);

        try {
            $reader = $this->getReader($import);
        }
        catch (\InvalidArgumentException $excep) {
            $now = new \DateTime();
            $import->setTotalCount(0);
            $import->setImportStartedAt($now);
            $import->setImportEndedAt($now);
            $import->buildExceptions(array($excep));
            return $import;
        }

        // Create the workflow from the reader
        $workflow = new Workflow\StepAggregator($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter($this->getWriter($import->getClass()));

        $this->addSteps($workflow, $import);

        // Process the workflow
        return $this->workflowProcess($workflow,$import);
    }

    /**
     *
     * @staticvar DoctrineWriter $doctrineWriter
     * @param string $class
     * @return DoctrineWriter
     * @throws \InvalidArgumentException
     */
    public function getWriter($class = null)
    {
        static $doctrineWriter = null;

        if ($doctrineWriter == null && $class == null) {
            throw new \InvalidArgumentException("The writer isn't yet initialized and we need to know the class we're dealing with");
        }

        // Create a writer: you need Doctrine’s EntityManager.
        if ($doctrineWriter == null) {
            $doctrineWriter = new DoctrineWriter($this->container->get('doctrine.orm.entity_manager'), $class, $this->duplicateFilter->getFields());
            $doctrineWriter->setTruncate(false);
        }

        return $doctrineWriter;
    }

    /**
     * @param Result $import
     * @return ReaderInterface
     * @throws \InvalidArgumentException
     */
    public function getReader(Result $import)
    {
        // Create and configure the reader
        $csvReader = new CsvReader($import->getImportFile()->openFile(), ',');

        // Tell the reader that the first row in the CSV file contains column headers
        $csvReader->setHeaderRowNumber(0);

        $fields  = $csvReader->getFields();
        $columns = $import->getMap()->getColumns();

        foreach ($columns as $column) {
            if ($column->getName() != $fields[$column->getOrder()]) {
                throw new \InvalidArgumentException(sprintf("%s != %s probably the wrong file or missing headers", $fields[$column->getOrder()], $column->getName()));
            }
        }

        return $csvReader;
    }

    /**
     * @param Workflow $workflow
     * @param Result $import
     * @return Duplicate $duplicate
     */
    public function addSteps(Workflow $workflow, Result $import)
    {
        // These map column headers i.e site_Code -> site
        $workflow->addStep($import->getMappings());
        // These allow us to ignore a column i.e. - region or country_ISO 
        $workflow->addStep($import->getIgnoredMapper());

        $valueConverter      = new ValueConverterStep();
        $valueConverterCount = 0;
        foreach ($import->getConverters() as $column) {
            $name = ($column->hasMapper()) ? $column->getMapper() : $column->getName();
            $valueConverter->add(sprintf('[%s]',  str_replace('.', '][', $name)), $this->container->get($column->getConverter()));
            $valueConverterCount++;
        }

        if ($valueConverterCount > 0) {
            $workflow->addStep($valueConverter);
        }

        $filterStep = new FilterStep();
        $addFilter  = false;

        if ($this->notBlankFilter) {
            $filterStep->add($this->notBlankFilter);
            $addFilter = true;
        }

        if ($this->duplicateFilter) {
            $filterStep->add($this->duplicateFilter);
            $addFilter = true;
        }

        if ($addFilter) {
            $workflow->addStep($filterStep);
        }
    }

    /**
     * @param Workflow $workflow
     * @return Result
     */
    public function workflowProcess(Workflow $workflow, Result $import)
    {
        try {
            $processResult = $workflow->process();
        }
        catch (DBALException $ex) {
            $now = new \DateTime();
            $import->setImportStartedAt($now);
            $import->setImportEndedAt($now);
            $import->setTotalCount(0);
            $import->buildExceptions(array($ex));

            return $import;
        }

        $import->setImportStartedAt($processResult->getStartTime());
        $import->setImportEndedAt($processResult->getEndTime());
        $import->setTotalCount($processResult->getTotalProcessedCount());
        $import->setDuplicates($this->duplicateFilter->toArray());
        $import->buildExceptions($processResult->getExceptions());
//        $import->setSuccesses($this->getWriter()->getResults());

        return $import;
    }

    /**
     * @return string
     */
    public function getMemoryLimit()
    {
        return $this->memoryLimit;
    }

    /**
     * @return integer
     */
    public function getMaxExecutionTime()
    {
        return $this->maxExecutionTime;
    }

    /**
     * @param string $memoryLimit
     * @return \NS\ImportBundle\Services\ImportProcessor
     */
    public function setMemoryLimit($memoryLimit)
    {
        $this->memoryLimit = $memoryLimit;
        return $this;
    }

    /**
     * @param integer $maxExecutionTime
     * @return \NS\ImportBundle\Services\ImportProcessor
     */
    public function setMaxExecutionTime($maxExecutionTime)
    {
        $this->maxExecutionTime = $maxExecutionTime;
        return $this;
    }

    /**
     * @param ContainerInterface $container
     * @return \NS\ImportBundle\Services\ImportProcessor
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return array
     */
    public function getNotBlank()
    {
        return $this->notBlankFilter;
    }

    /**
     * @param NotBlank $notBlankFilter
     * @return \NS\ImportBundle\Services\ImportProcessor
     */
    public function setNotBlank(NotBlank $notBlankFilter)
    {
        $this->notBlankFilter = $notBlankFilter;
        return $this;
    }

    /**
     * @return Duplicate
     */
    public function getDuplicate()
    {
        return $this->duplicateFilter;
    }

    /**
     * @param Duplicate $duplicate
     * @return \NS\ImportBundle\Services\ImportProcessor
     */
    public function setDuplicate(Duplicate $duplicate)
    {
        $this->duplicateFilter = $duplicate;
        return $this;
    }
}
