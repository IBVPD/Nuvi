<?php

namespace NS\ImportBundle\Importer;

use DateInterval;
use DateTime;
use Ddeboer\DataImport\Filter\OffsetFilter;
use Ddeboer\DataImport\Reader;
use Ddeboer\DataImport\Result;
use Ddeboer\DataImport\Step\ConverterStep;
use Ddeboer\DataImport\Step\FilterStep;
use Ddeboer\DataImport\Step\MappingStep;
use Ddeboer\DataImport\Step\ValueConverterStep;
use Ddeboer\DataImport\Workflow;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use InvalidArgumentException;
use NS\ImportBundle\Converter\DateOfBirthConverter;
use NS\ImportBundle\Converter\DateRangeConverter;
use NS\ImportBundle\Converter\Expression\ExpressionBuilder;
use NS\ImportBundle\Converter\PreprocessorStep;
use NS\ImportBundle\Converter\Registry;
use NS\ImportBundle\Converter\TrimInputConverter;
use NS\ImportBundle\Converter\UnsetMappingItemConverter;
use NS\ImportBundle\Converter\WarningConverter;
use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Exceptions\CaseLinkerNotFoundException;
use NS\ImportBundle\Filter\Duplicate;
use NS\ImportBundle\Filter\NotBlank;
use NS\ImportBundle\Linker\CaseLinkerRegistry;
use NS\ImportBundle\Reader\ExcelReader;
use NS\ImportBundle\Reader\OffsetableReaderInterface;
use NS\ImportBundle\Reader\ReaderFactory;
use NS\ImportBundle\Writer\DoctrineWriter;
use NS\SentinelBundle\Entity\ReferenceLab;

/**
 * Description of ImportProcessor
 *
 * @author gnat
 */
class ImportProcessor
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ObjectManager
     */
    private $entityMgr;

    /**
     * @var Duplicate
     */
    private $duplicateFilter;

    /**
     * @var NotBlank
     */
    private $notBlankFilter;

    /**
     * @var DoctrineWriter
     */
    private $doctrineWriter;

    /**
     * @var CaseLinkerRegistry
     */
    private $linkerRegistry;

    /**
     * @var integer
     */
    private $limit  = null;

    /**
     * @param Registry $registry
     * @param ObjectManager $entityMgr
     * @param CaseLinkerRegistry $linkerRegistry
     */
    public function __construct(Registry $registry, ObjectManager $entityMgr, CaseLinkerRegistry $linkerRegistry)
    {
        $this->registry = $registry;
        $this->entityMgr = $entityMgr;
        $this->linkerRegistry = $linkerRegistry;
    }

    /**
     * @param Import $import
     * @return Result
     * @throws Exception
     */
    public function process(Import $import): Result
    {
        $reader = $this->getReader($import);

        if ($reader instanceof OffsetableReaderInterface) {
            // Move to current position
            $offset = ($reader instanceof ExcelReader) ? -1:0;
            $reader->setOffset($import->getPosition() - $offset);
        }

        $linker = $this->linkerRegistry->getLinker($import->getCaseLinkerId());

        // Create the workflow from the reader
        $workflow = new Workflow\StepAggregator($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter($this->getWriter($import->getClass(), $linker->getCriteria(), $linker->getRepositoryMethod()));

        $this->addSteps($workflow, $import);

        // Process the workflow
        $result = $workflow->process();
        if ($this->duplicateFilter) {
            $this->duplicateFilter->finish();
        }

        return $result;
    }

    /**
     * @param $linkerName
     * @return mixed
     */
    public function getLinker($linkerName)
    {
        if (isset($this->caseLinkers[$linkerName])) {
            return $this->caseLinkers[$linkerName];
        }

        throw new CaseLinkerNotFoundException('Unable to locate case linker with id %s');
    }

    /**
     *
     * @param string $class
     * @param array $lookupFields
     * @param string $entityRepositoryMethod
     * @return DoctrineWriter
     * @throws InvalidArgumentException
     */
    public function getWriter($class, $lookupFields = null, $entityRepositoryMethod = null): DoctrineWriter
    {
        if ($this->doctrineWriter === null || $this->doctrineWriter->getEntityName() != $class) {
            if ($lookupFields ===null || $entityRepositoryMethod === null) {
                throw new InvalidArgumentException("When creating a new writer, the lookupFields and entityRepositoryMethod arguments are required");
            }
            $this->doctrineWriter = new DoctrineWriter($this->entityMgr, $class, $lookupFields);
            $this->doctrineWriter->setTruncate(false);
            $this->doctrineWriter->setClearOnFlush(false);
            $this->doctrineWriter->setEntityRepositoryMethod($entityRepositoryMethod);
        }

        return $this->doctrineWriter;
    }

    /**
     * @param Import $import
     * @return Reader $csvReader
     */
    public function getReader(Import $import): Reader
    {
        // Create and configure the reader
        $factory = new ReaderFactory();
        $reader = $factory->getReader($import->getSourceFile());
        $reader->setHeaderRowNumber($import->getMap()->getHeaderRow()-1);

        $import->setSourceCount($reader->count());

        $fields = $reader->getFields();
        $columns = $import->getMap()->getColumns();

        foreach ($columns as $column) {
            if (!in_array($column->getName(), $fields)) {
                throw new InvalidArgumentException(sprintf("Missing field '%s'! Perhaps you've uploaded the wrong file?", $column->getName()));
            }
        }

        return $reader;
    }

    public function addSteps(Workflow $workflow, Import $import): void
    {
        $this->addOffsetStep($workflow, 80);

        $this->addDroppedColumnStep($workflow, $import, 70);

        $this->addCleaningStep($workflow, 60);

        $this->addPreProcessorStep($workflow, $import, 50);

        $this->addColumnNameMappingStep($workflow, $import, 40);

        $this->addReferenceLabLinkingStep($workflow, $import, 35);

        $this->addColumnValueConversionStep($workflow, $import, 30);

        $this->addFilterStep($workflow, 20);

        $this->addWarningStep($workflow, $import, 10);
    }

    public function addOffsetStep(Workflow $workflow, int $priority = 80): void
    {
        $offsetFilter = new FilterStep();
        $offsetFilter->add(new OffsetFilter(0, $this->getLimit(), true));

        // Stop processing after limit
        $workflow->addStep($offsetFilter, $priority);
    }

    public function addCleaningStep(Workflow $workflow, int $priority = 60): void
    {
        // Trim all input
        $workflow->addStep(new ConverterStep([new TrimInputConverter()]), $priority);
    }

    public function addDroppedColumnStep(Workflow $workflow, Import $import, int $priority = 70): void
    {
        // These allow us to ignore a column i.e. - region or country_ISO
        $mappings = $import->getIgnoredMapper();
        if (!empty($mappings)) {
            $workflow->addStep(new UnsetMappingItemConverter($mappings), $priority);
        }
    }

    public function addPreProcessorStep(Workflow $workflow, Import $import, int $priority = 50): void
    {
        $allConditions = $import->getPreprocessor();
        if (!empty($allConditions)) {
            $processor = new PreprocessorStep(new ExpressionBuilder());
            foreach ($allConditions as $name => $conditions) {
                $processor->add($name, $conditions);
            }

            $workflow->addStep($processor, $priority);
        }
    }

    public function addColumnNameMappingStep(Workflow $workflow, Import $import, int $priority = 40): void
    {
        // These map column headers i.e site_Code -> site
        $mappings = $import->getMappings();
        if (!empty($mappings)) {
            $workflow->addStep(new MappingStep($mappings), $priority);
        }
    }

    /** @var ReferenceLab|null */
    private $lab;

    /**
     * @param Workflow $workflow
     * @param Import $import
     * @param int $priority
     */
    public function addReferenceLabLinkingStep(Workflow $workflow, Import $import, int $priority = 35): void
    {
        if ($import->hasReferenceLabResults()) {
            $this->lab = $import->getReferenceLab();

            $step = new ConverterStep();
            $step->add([$this, 'addReferenceLabConverter']);

            $workflow->addStep($step, $priority);
        }
    }

    public function addReferenceLabConverter(array $item): array
    {
        if (isset($item['referenceLab'])) {
            $item['referenceLab']['lab'] = $this->lab;
        }

        return $item;
    }

    public function addColumnValueConversionStep(Workflow $workflow, Import $import, int $priority = 30): void
    {
        $converters = $import->getConverters();
        if (!empty($converters)) {
            $valueConverter = new ValueConverterStep();


            foreach ($converters as $column) {
                $name = $column->hasMapper() ? $column->getMapper() : $column->getName();
                $valueConverter->add(sprintf('[%s]', str_replace('.', '][', $name)), $this->registry->get($column->getConverter()));
            }

            $workflow->addStep($valueConverter, $priority);
        }
    }

    public function addFilterStep(Workflow $workflow, int $priority = 20): void
    {
        $filterStep = new FilterStep();
//        $filterStep->add(new DateOfBirthFilter());

        if ($this->notBlankFilter) {
            $filterStep->add($this->notBlankFilter);
        }

        if ($this->duplicateFilter) {
            $filterStep->add($this->getDuplicate());
        }

        $workflow->addStep($filterStep, $priority);
    }

    public function addWarningStep(Workflow $workflow, Import $import, int $priority = 10): void
    {
        // Adds warnings for out of range values
        $converter = new ConverterStep();
        $converter->add(new DateOfBirthConverter());
        $converter->add(new WarningConverter());
        $converter->add(new DateRangeConverter(new DateTime(), null, true));

        $start = clone $import->getInputDateStart();
        $start->sub(new DateInterval('P5Y'));

        $converter->add(new DateRangeConverter($import->getInputDateEnd(), $start, true));

        $workflow->addStep($converter, $priority);
    }

    public function getNotBlank(): ?NotBlank
    {
        return $this->notBlankFilter;
    }

    public function setNotBlank(NotBlank $notBlankFilter): void
    {
        $this->notBlankFilter = $notBlankFilter;
    }

    public function getDuplicate(): ?Duplicate
    {
        return $this->duplicateFilter;
    }

    public function setDuplicate(Duplicate $duplicate): void
    {
        $this->duplicateFilter = $duplicate;
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
     */
    public function setLimit($limit): void
    {
        $this->limit = $limit;
    }
}
