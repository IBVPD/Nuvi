<?php

namespace NS\ImportBundle\Importer;

use \Ddeboer\DataImport\Filter\OffsetFilter;
use \Ddeboer\DataImport\Step\ConverterStep;
use \Ddeboer\DataImport\Workflow;
use \Ddeboer\DataImport\Reader;
use \Ddeboer\DataImport\Step\FilterStep;
use \Ddeboer\DataImport\Step\ValueConverterStep;
use \Doctrine\Common\Persistence\ObjectManager;
use \NS\ImportBundle\Converter\DateRangeConverter;
use \NS\ImportBundle\Converter\Registry;
use \NS\ImportBundle\Converter\TrimInputConverter;
use \NS\ImportBundle\Converter\WarningConverter;
use \NS\ImportBundle\Entity\Import;
use \NS\ImportBundle\Filter\DateOfBirthFilter;
use \NS\ImportBundle\Filter\Duplicate;
use \NS\ImportBundle\Filter\NotBlank;
use \NS\ImportBundle\Reader\ExcelReader;
use \NS\ImportBundle\Reader\OffsetableReaderInterface;
use \NS\ImportBundle\Writer\DoctrineWriter;
use \Ddeboer\DataImport\Result;
use \NS\ImportBundle\Reader\ReaderFactory;

/**
 * Description of ImportProcessor
 *
 * @author gnat
 */
class ImportProcessor
{
    private $registry;
    private $entityMgr;
    private $duplicateFilter;
    private $notBlankFilter;
    private $doctrineWriter;

    private $limit  = null;

    /**
     * @param Registry $registry
     * @param ObjectManager $entityMgr
     */
    public function __construct(Registry $registry, ObjectManager $entityMgr)
    {
        $this->registry = $registry;
        $this->entityMgr = $entityMgr;
    }

    /**
     * @param Import $import
     * @return Result
     * @throws \Exception
     */
    public function process(Import $import)
    {
        $reader = $this->getReader($import);

        if($reader instanceof OffsetableReaderInterface) {
            // Move to current position
            $offset = ($reader instanceof ExcelReader) ? -1:0;
            $reader->setOffset($import->getPosition() - $offset);
        }

        // Create the workflow from the reader
        $workflow = new Workflow\StepAggregator($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter($this->getWriter($import->getClass()));

        $this->addSteps($workflow, $import);

        // Process the workflow
        $result = $workflow->process();
        if($this->duplicateFilter) {
            $this->duplicateFilter->finish();
        }

        return $result;
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
            $this->doctrineWriter = new DoctrineWriter($this->entityMgr, $class, array('getcode' => 'site', 1 => 'caseId'));
            $this->doctrineWriter->setTruncate(false);
            $this->doctrineWriter->setClearOnFlush(false);
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
        $reader = ReaderFactory::getReader($import->getSourceFile());
        $reader->setHeaderRowNumber(0);

        $import->setSourceCount($reader->count());

        $fields = $reader->getFields();
        $columns = $import->getMap()->getColumns();

        foreach ($columns as $column) {
            if (!in_array($column->getName(),$fields)) {
                throw new \InvalidArgumentException(sprintf("Missing field '%s'! Perhaps you've uploaded the wrong file?", $column->getName()));
            }
        }

        return $reader;
    }

    /**
     * @param Workflow $workflow
     * @param Import $import
     * @return Duplicate $duplicate
     */
    public function addSteps(Workflow $workflow, Import $import)
    {
        $this->addOffsetStep($workflow, 80);

        $this->addDroppedColumnStep($workflow,$import,70);

        $this->addCleaningStep($workflow,60);

        $this->addPreProcessorStep($workflow,$import, 50);

        $this->addColumnNameMappingStep($workflow,$import, 40);

        $this->addColumnValueConversionStep($workflow,$import, 30);

        $this->addFilterStep($workflow, 20);

        $this->addWarningStep($workflow, $import, 10);
    }

    /**
     * @param Workflow $workflow
     * @param int $priority
     */
    public function addOffsetStep(Workflow $workflow, $priority = 80)
    {
        $offsetFilter = new FilterStep();
        $offsetFilter->add(new OffsetFilter(0, $this->getLimit(), true));

        // Stop processing after limit
        $workflow->addStep($offsetFilter, $priority);
    }

    /**
     * @param Workflow $workflow
     * @param int $priority
     */
    public function addCleaningStep(Workflow $workflow, $priority = 60)
    {
        // Trim all input
        $workflow->addStep(new ConverterStep(array(new TrimInputConverter())), $priority);
    }

    /**
     * @param Workflow $workflow
     * @param Import $import
     * @param int $priority
     */
    public function addDroppedColumnStep(Workflow $workflow, Import $import, $priority = 70)
    {
        // These allow us to ignore a column i.e. - region or country_ISO
        $workflow->addStep($import->getIgnoredMapper(), $priority);
    }

    /**
     * @param Workflow $workflow
     * @param Import $import
     * @param int $priority
     */
    public function addPreProcessorStep(Workflow $workflow, Import $import, $priority = 50)
    {
        $preProcessor = $import->getPreprocessor();
        if ($preProcessor) {
            $workflow->addStep($preProcessor, $priority);
        }
    }

    /**
     * @param Workflow $workflow
     * @param Import $import
     * @param int $priority
     */
    public function addColumnNameMappingStep(Workflow $workflow, Import $import, $priority = 40)
    {
        // These map column headers i.e site_Code -> site
        $workflow->addStep($import->getMappings(), $priority);

    }

    /**
     * @param Workflow $workflow
     * @param Import $import
     * @param int $priority
     */
    public function addColumnValueConversionStep(Workflow $workflow, Import $import, $priority = 30)
    {
        $valueConverter = new ValueConverterStep();
        $valueConverterCount = 0;
        foreach ($import->getConverters() as $column) {
            $name = ($column->hasMapper()) ? $column->getMapper() : $column->getName();
            $valueConverter->add(sprintf('[%s]', str_replace('.', '][', $name)), $this->registry->get($column->getConverter()));
            $valueConverterCount++;
        }

        if ($valueConverterCount > 0) {
            $workflow->addStep($valueConverter, $priority);
        }
    }

    /**
     * @param Workflow $workflow
     * @param int $priority
     */
    public function addFilterStep(Workflow $workflow, $priority = 20)
    {
        $filterStep = new FilterStep();
        $filterStep->add(new DateOfBirthFilter());

        if ($this->notBlankFilter) {
            $filterStep->add($this->notBlankFilter);
        }

        if ($this->duplicateFilter) {
            $filterStep->add($this->getDuplicate());
        }

        $workflow->addStep($filterStep, $priority);
    }

    /**
     * @param Workflow $workflow
     * @param int $priority
     */
    public function addWarningStep(Workflow $workflow, Import $import, $priority = 10)
    {
        // Adds warnings for out of range values
        $converter = new ConverterStep();
        $converter->add(new WarningConverter());
        $converter->add(new DateRangeConverter(new \DateTime()));

        $start = clone $import->getInputDateStart();
        $start->sub(new \DateInterval('P5Y'));

        $converter->add(new DateRangeConverter($import->getInputDateEnd(), $start));

        $workflow->addStep($converter, $priority);
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
