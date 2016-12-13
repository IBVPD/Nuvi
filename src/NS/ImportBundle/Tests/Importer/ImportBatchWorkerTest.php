<?php

namespace NS\ImportBundle\Tests\Importer;

use NS\ImportBundle\Importer\ImportBatchWorker;
use NS\ImportBundle\Linker\CaseLinkerRegistry;

class ImportBatchWorkerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        list($mockEntityMgr, $mockProcessor, $mockLinker) = $this->getConstructorArguments();
        $worker = new ImportBatchWorker($mockEntityMgr, $mockProcessor, $mockLinker);
        $this->assertInstanceOf('NS\ImportBundle\Importer\ImportBatchWorker', $worker);
    }

    public function getConstructorArguments()
    {
        $entityMgr = $this->createMock('Doctrine\Common\Persistence\ObjectManager');

        $processor = $this->createMock('NS\ImportBundle\Importer\ImportProcessor');

        $mockLinkerReg = new CaseLinkerRegistry();
        return [$entityMgr,$processor,$mockLinkerReg];
    }
}
