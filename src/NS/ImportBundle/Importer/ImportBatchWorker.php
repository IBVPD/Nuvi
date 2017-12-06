<?php

namespace NS\ImportBundle\Importer;

use Ddeboer\DataImport\Result;
use Doctrine\Common\Persistence\ObjectManager;
use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Filter\Duplicate;
use NS\ImportBundle\Filter\NotBlank;
use NS\ImportBundle\Linker\CaseLinkerRegistry;

/**
 * Class ImportBatchWorker
 * @package NS\ImportBundle\Importer
 */
class ImportBatchWorker
{
    /**
     * @var ObjectManager
     */
    private $entityMgr;

    /**
     * @var ImportProcessor
     */
    private $processor;

    /**
     * @var CaseLinkerRegistry
     */
    private $linkerRegistry;

    /**
     * @param ObjectManager $entityMgr
     * @param ImportProcessor $processor
     * @param CaseLinkerRegistry $linkerRegistry
     */
    public function __construct(ObjectManager $entityMgr, ImportProcessor $processor, CaseLinkerRegistry $linkerRegistry)
    {
        $this->entityMgr = $entityMgr;
        $this->processor = $processor;
        $this->linkerRegistry = $linkerRegistry;
    }

    /**
     * @param Import $import Import
     * @param int $batchSize Number of rows to process at a time
     *
     * @return bool Returns true when the import has been completely processed
     * @throws \NS\ImportBundle\Linker\CaseLinkerNotFoundException
     */
    public function consume(Import $import, $batchSize = 500)
    {
        $this->setup($import, $batchSize);
        $result = $this->process($import);
        $this->finish($import, $result);
        if($import->isComplete()) {}
        return $import->isComplete();
    }

    /**
     * @param Import $import
     * @param $batchSize
     * @throws \NS\ImportBundle\Linker\CaseLinkerNotFoundException
     */
    public function setup(Import $import, $batchSize)
    {
        $linker = $this->linkerRegistry->getLinker($import->getCaseLinkerId());

        $this->processor->setDuplicate(new Duplicate($linker->getCriteria(), $import->getDuplicateFile()));
        $this->processor->setNotBlank(new NotBlank($linker->getCriteria()));
        $this->processor->setLimit($batchSize);
    }

    /**
     * @param Import $import
     * @return Result
     */
    public function process(Import $import)
    {
        $result = $this->processor->process($import);
        $this->entityMgr->flush();

        return $result;
    }

    /**
     * @param Import $import
     * @param Result $result
     */
    public function finish(Import $import, Result $result)
    {
        $updater = new ImportResultUpdater();
        $updater->update($import, $result, $this->processor->getWriter($import->getClass())->getResults());
        $import->setStatus($import->isComplete() ? Import::STATUS_COMPLETE : Import::STATUS_RUNNING);

        $this->entityMgr->persist($import);
        $this->entityMgr->flush();
    }
}
