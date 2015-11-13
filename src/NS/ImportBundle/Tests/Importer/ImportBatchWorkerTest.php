<?php

namespace NS\ImportBundle\Tests\Importer;

use NS\ImportBundle\Importer\ImportBatchWorker;
use NS\ImportBundle\Linker\CaseLinkerRegistry;

class ImportBatchWorkerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        list($mockEntityMgr,$mockProcessor,$mockLinker) = $this->getConstructorArguments();
        $worker = new ImportBatchWorker($mockEntityMgr,$mockProcessor,$mockLinker);
        $this->assertInstanceOf('NS\ImportBundle\Importer\ImportBatchWorker', $worker);
    }

    public function getConstructorArguments()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $processor = $this->getMockBuilder('NS\ImportBundle\Importer\ImportProcessor')
            ->disableOriginalConstructor()
            ->getMock();

        $mockLinkerReg = new CaseLinkerRegistry();
        return array($entityMgr,$processor,$mockLinkerReg);
    }
}
