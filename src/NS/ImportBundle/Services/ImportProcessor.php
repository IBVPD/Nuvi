<?php

namespace NS\ImportBundle\Services;

use \Ddeboer\DataImport\Reader\CsvReader;
use \Ddeboer\DataImport\Workflow;
use \Ddeboer\DataImport\Reader\ReaderInterface;
use \Ddeboer\DataImport\Step\FilterStep;
use \Ddeboer\DataImport\Step\ValueConverterStep;
use \Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\DBAL\DBALException;
use \InvalidArgumentException;
use \NS\ImportBundle\Entity\Result;
use \NS\ImportBundle\Filter\Duplicate;
use \NS\ImportBundle\Filter\DuplicateFilterFactory;
use \NS\ImportBundle\Filter\LinkerFilterFactory;
use \NS\ImportBundle\Filter\NotBlank;
use \NS\ImportBundle\Filter\NotBlankFilterFactory;
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
    private $duplicateFactory;
    private $duplicate;
    private $notBlankFactory;
    private $notBlank;
    private $linkerFactory;
    private $linkers;
    private $memoryLimit = '1024M';
    private $maxExecutionTime = 190;

    /**
     * @param ObjectManager $entityMgr
     * @param ContainerInterface $container
     * @param DuplicateFilterFactory $duplicateFactory
     * @param NotBlankFilterFactory $notBlankFactory
     * @param LinkerFilterFactory $linkerFactory
     */
    public function __construct(ContainerInterface $container, DuplicateFilterFactory $duplicateFactory, NotBlankFilterFactory $notBlankFactory, LinkerFilterFactory $linkerFactory)
    {
        $this->setContainer($container);
        $this->setDuplicateFactory($duplicateFactory);
        $this->setNotBlankFactory($notBlankFactory);
        $this->linkerFactory = $linkerFactory;
    }

    /**
     * @param Result $import
     * @return Result
     */
    public function process(Result $import)
    {
        ini_set('max_execution_time', $this->maxExecutionTime);
        ini_set('memory_limit', $this->memoryLimit);

        $this->initializeDuplicateFilter($import);
        $this->initializeNotBlankFilter($import);

        try {
            $reader = $this->getReader($import);
        }
        catch (InvalidArgumentException $excep) {
            $now = new \DateTime();
            $import->setTotalCount(0);
            $import->setImportStartedAt($now);
            $import->setImportEndedAt($now);
            $import->buildExceptions(array($excep));
            return $import;
        }

        // Create the workflow from the reader
        $workflow = new Workflow($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter($this->getWriter($import->getClass()));

        $this->addFilters($workflow, $import);

        // Process the workflow
        return $this->workflowProcess($workflow,$import);
    }

    /**
     *
     * @staticvar DoctrineWriter $doctrineWriter
     * @param string $class
     * @return DoctrineWriter
     * @throws InvalidArgumentException
     */
    public function getWriter($class = null)
    {
        static $doctrineWriter = null;

        if ($doctrineWriter == null && $class == null) {
            throw new InvalidArgumentException("The writer isn't yet initialized and we need to know the class we're dealing with");
        }

        // Create a writer: you need Doctrine’s EntityManager.
        if ($doctrineWriter == null) {
            $doctrineWriter = new DoctrineWriter($this->container->get('ns.model_manager'), $class, $this->duplicate->getFields());
            $doctrineWriter->setTruncate(false);
        }

        return $doctrineWriter;
    }

    /**
     * @param Result $import
     * @return ReaderInterface
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
            if ($column->getName() != $fields[$column->getOrder()])
                throw new InvalidArgumentException(sprintf("%s != %s probably the wrong file or missing headers", $fields[$column->getOrder()], $column->getName()));
        }

        return $csvReader;
    }

    /**
     * @param Workflow $workflow
     * @param Result $import
     * @return Duplicate $duplicate
     */
    public function addFilters(Workflow $workflow, Result $import)
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

        if (!$this->notBlank) {
            $this->initializeNotBlankFilter($import);
        }

        if (!$this->duplicate) {
            $this->initializeDuplicateFilter($import);
        }

        $filterStep      = new FilterStep();
        $filterStepCount = 0;
        if ($this->notBlank) {
            $filterStep->add($this->notBlank);
            $filterStepCount++;
        }

        if ($this->duplicate) {
            $filterStep->add($this->duplicate);
            $filterStepCount++;
        }

        if ($filterStepCount > 0) {
            $workflow->addStep($filterStep);
        }

//        if (!$this->linkers) {
//            $this->initializeLinkerFilter($import);
//        }
//
//        if ($this->linkers) {
//            foreach($this->linkers as $linkerConverter) {
//                $workflow->addObjectLinker($linkerConverter);
//            }
//        }
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
        $import->setDuplicates($this->duplicate);
        $import->buildExceptions($processResult->getExceptions());
        $import->setResults($this->getWriter()->getResults());

        return $import;
    }

    /**
     * @return array
     */
    public function getNotBlank()
    {
        return $this->notBlank;
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
     * @param NotBlank $notBlankFilter
     * @return \NS\ImportBundle\Services\ImportProcessor
     */
    public function setNotBlank(NotBlank $notBlankFilter)
    {
        $this->notBlank = $notBlankFilter;
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

    /**
     * @return DuplicateFilterFactory
     */
    public function getDuplicateFactory()
    {
        return $this->duplicateFactory;
    }

    /**
     *
     * @param DuplicateFilterFactory $duplicateFactory
     * @return \NS\ImportBundle\Services\ImportProcessor
     */
    public function setDuplicateFactory(DuplicateFilterFactory $duplicateFactory)
    {
        $this->duplicateFactory = $duplicateFactory;
        return $this;
    }

    /**
     *
     * @return NotBlankFilterFactory
     */
    public function getNotBlankFactory()
    {
        return $this->notBlankFactory;
    }

    /**
     *
     * @param NotBlankFilterFactory $notBlankFactory
     * @return \NS\ImportBundle\Services\ImportProcessor
     */
    public function setNotBlankFactory(NotBlankFilterFactory $notBlankFactory)
    {
        $this->notBlankFactory = $notBlankFactory;
        return $this;
    }

    /**
     * @param Result $import
     */
    public function initializeDuplicateFilter(Result $import)
    {
        $this->duplicate = $this->duplicateFactory->createFilter($import->getClass());
    }

    /**
     * @param Result $import
     */
    public function initializeNotBlankFilter(Result $import)
    {
        $this->notBlank = $this->notBlankFactory->createFilter($import->getClass());
    }

    public function getLinkerFactory()
    {
        return $this->linkerFactory;
    }

    public function setLinkerFactory($linkerFactory)
    {
        $this->linkerFactory = $linkerFactory;
        return $this;
    }

    public function initializeLinkerFilter(Result $import)
    {
        $this->linkers = $this->linkerFactory->createFilter($import->getClass());
    }
}