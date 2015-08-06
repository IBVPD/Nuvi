<?php

namespace NS\ImportBundle\Importer;

use Ddeboer\DataImport\Filter\OffsetFilter;
use \Ddeboer\DataImport\Reader\CsvReader;
use \Ddeboer\DataImport\Workflow;
use \Ddeboer\DataImport\Reader;
use \Ddeboer\DataImport\Step\FilterStep;
use \Ddeboer\DataImport\Step\ValueConverterStep;
use \Doctrine\DBAL\DBALException;
use NS\ImportBundle\Entity\Import;
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
    private $doctrineWriter;

    private $memoryLimit = '1024M';
    private $maxExecutionTime = 190;
    private $limit  = null;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * @param Import $import
     * @return Result
     * @throws \Exception
     */
    public function process(Import $import)
    {
        ini_set('max_execution_time', $this->maxExecutionTime);
        ini_set('memory_limit', $this->memoryLimit);

        $reader = $this->getReader($import);

        // Create the workflow from the reader
        $workflow = new Workflow\StepAggregator($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter($this->getWriter($import->getClass()));

        $this->addSteps($workflow, $import);

        // Process the workflow
        return $workflow->process();
    }

    /**
     *
     * @param string $class
     * @return DoctrineWriter
     * @throws \InvalidArgumentException
     */
    public function getWriter($class)
    {
        if ($this->doctrineWriter === null || $this->doctrineWriter->getEntityName() != $class) {
            $this->doctrineWriter = new DoctrineWriter($this->container->get('doctrine.orm.entity_manager'), $class, array('getcode' => 'site', 1 => 'caseId'));
            $this->doctrineWriter->setTruncate(false);
            $this->doctrineWriter->setEntityRepositoryMethod('findWithRelations');
        }

        return $this->doctrineWriter;
    }

    /**
     * @param Import $import
     * @return Reader $csvReader
     */
    public function getReader(Import $import)
    {
        // Create and configure the reader
        $csvReader = new CsvReader($import->getSourceFile()->openFile(), ',');

        // Tell the reader that the first row in the CSV file contains column headers
        $csvReader->setHeaderRowNumber(0);

        $import->setSourceCount($csvReader->count());

        $fields = $csvReader->getFields();
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
     * @param Import $import
     * @return Duplicate $duplicate
     */
    public function addSteps(Workflow $workflow, Import $import)
    {
        $offsetFilter = new FilterStep();
        $offsetFilter->add(new OffsetFilter($import->getPosition(),$this->getLimit()));
        // Move to current position
        $workflow->addStep($offsetFilter,5);

        // These map column headers i.e site_Code -> site
        $workflow->addStep($import->getMappings(),4);

        // These allow us to ignore a column i.e. - region or country_ISO 
        $workflow->addStep($import->getIgnoredMapper(),3);

        $valueConverter = new ValueConverterStep();
        $valueConverterCount = 0;
        foreach ($import->getConverters() as $column) {
            $name = ($column->hasMapper()) ? $column->getMapper() : $column->getName();
            $valueConverter->add(sprintf('[%s]', str_replace('.', '][', $name)), $this->container->get($column->getConverter()));
            $valueConverterCount++;
        }

        if ($valueConverterCount > 0) {
            $workflow->addStep($valueConverter,2);
        }

        $filterStep = new FilterStep();
        $addFilter = false;

        if ($this->notBlankFilter) {
            $filterStep->add($this->notBlankFilter);
            $addFilter = true;
        }

        if ($this->duplicateFilter) {
            $filterStep->add($this->getDuplicate());
            $addFilter = true;
        }

        if ($addFilter) {
            $workflow->addStep($filterStep,1);
        }
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

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     * @return ImportProcessor
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
}
