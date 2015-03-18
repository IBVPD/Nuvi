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
    private $container;

    private $duplicate;

    private $notBlankFields;

    private $memoryLimit      = '512M';

    private $maxExecutionTime = 90;

    /**
     * @param ObjectManager $entityMgr
     * @param ContainerInterface $container
     * @param Duplicate $duplicateFilter
     * @param array|string $notBlankFields
     */
    public function __construct(ContainerInterface $container, Duplicate $duplicateFilter, $notBlankFields)
    {
        $this->setContainer($container);
        $this->setDuplicate($duplicateFilter);
        $this->setNotBlankFields($notBlankFields);
    }

    /**
     * @param Import $import
     * @return Result
     */
    public function process(Import $import)
    {
        ini_set('max_execution_time', $this->maxExecutionTime);
        ini_set('memory_limit', $this->memoryLimit);

        try
        {
            $reader = $this->getReader($import);
        }
        catch (\InvalidArgumentException $excep)
        {
            $now = new \DateTime();
            return new Result("Error", $now, $now, 0, $this->duplicate, array($excep));
        }

        // Create the workflow from the reader
        $workflow = new Workflow($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter($this->getWriter($import->getClass()));

        $this->addFilters($workflow, $import);

        // Process the workflow
        return $this->workflowProcess($workflow);
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

        if ($doctrineWriter == null && $class == null)
            throw new \InvalidArgumentException("The writer isn't yet initialized and we need to know the class we're dealing with");

        // Create a writer: you need Doctrine’s EntityManager.
        if ($doctrineWriter == null)
        {
            $doctrineWriter = new DoctrineWriter($this->container->get('doctrine.orm.entity_manager'), $class, $this->duplicate->getFields());
            $doctrineWriter->setTruncate(false);
        }

        return $doctrineWriter;
    }

    /**
     * @param Import $import
     * @return ReaderInterface
     */
    public function getReader(Import $import)
    {
        // Create and configure the reader
        $csvReader = new CsvReader($import->getFile()->openFile(), ',');

        // Tell the reader that the first row in the CSV file contains column headers
        $csvReader->setHeaderRowNumber(0);

        $fields  = $csvReader->getFields();
        $columns = $import->getMap()->getColumns();

        foreach ($columns as $column)
        {
            if ($column->getName() != $fields[$column->getOrder()])
                throw new \InvalidArgumentException(sprintf("%s != %s probably the wrong file or missing headers", $fields[$column->getOrder()], $column->getName()));
        }

        return $csvReader;
    }

    /**
     * @param Workflow $workflow
     * @param Import $import
     * @return Duplicate $duplicate
     */
    public function addFilters(Workflow $workflow, Import $import)
    {
        foreach ($import->getConverters() as $column)
        {
            $name = ($column->hasMapper()) ? $column->getMapper() : $column->getName();
            $workflow->addValueConverter($name, $this->container->get($column->getConverter()));
        }

        $workflow->addItemConverter($import->getMappings());
        $workflow->addItemConverter($import->getIgnoredMapper());
        $workflow->addFilterAfterConversion(new NotBlank($this->notBlankFields));
        $workflow->addFilterAfterConversion($this->duplicate);
    }

    /**
     * @param Workflow $workflow
     * @return Result
     */
    public function workflowProcess(Workflow $workflow)
    {
        try
        {
            $processResult = $workflow->process();
        }
        catch (DBALException $ex)
        {
            $now = new \DateTime();
            return new Result("Error", $now, $now, 0, $this->duplicate, array($ex));
        }

        $result = new Result($processResult->getName(), $processResult->getStartTime(), $processResult->getEndTime(), $processResult->getTotalProcessedCount(), $this->duplicate, $processResult->getExceptions());
        $result->setResults($this->getWriter()->getResults());

        return $result;
    }

    /**
     * @return array
     */
    public function getNotBlankFields()
    {
        return $this->notBlankFields;
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
     * @param string|array $notBlankFields
     * @return \NS\ImportBundle\Services\ImportProcessor
     */
    public function setNotBlankFields($notBlankFields)
    {
        $this->notBlankFields = (is_array($notBlankFields)) ? $notBlankFields : array($notBlankFields);
        return $this;
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
     * @return Duplicate
     */
    public function getDuplicate()
    {
        return $this->duplicate;
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
     * @param Duplicate $duplicate
     * @return \NS\ImportBundle\Services\ImportProcessor
     */
    public function setDuplicate(Duplicate $duplicate)
    {
        $this->duplicate = $duplicate;
        return $this;
    }
}