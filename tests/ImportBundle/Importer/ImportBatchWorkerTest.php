<?php

namespace NS\ImportBundle\Tests\Importer;

use NS\ImportBundle\Importer\ImportBatchWorker;
use NS\ImportBundle\Linker\CaseLinkerRegistry;
use PHPUnit\Framework\TestCase;
use NS\ImportBundle\Importer\ImportProcessor;
use Doctrine\Common\Persistence\ObjectManager;

class ImportBatchWorkerTest extends TestCase
{
    public function testConstructor(): void
    {
        [$mockEntityMgr, $mockProcessor, $mockLinker] = $this->getConstructorArguments();
        $worker = new ImportBatchWorker($mockEntityMgr, $mockProcessor, $mockLinker);
        $this->assertInstanceOf(ImportBatchWorker::class, $worker);
    }

    public function getConstructorArguments(): array
    {
        $entityMgr = $this->createMock(ObjectManager::class);

        $processor = $this->createMock(ImportProcessor::class);

        $mockLinkerReg = new CaseLinkerRegistry();
        return [$entityMgr,$processor,$mockLinkerReg];
    }
}
