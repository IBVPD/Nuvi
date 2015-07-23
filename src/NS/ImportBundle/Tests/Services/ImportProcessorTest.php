<?php

namespace NS\ImportBundle\Tests\Services;

use \Ddeboer\DataImport\Reader\ArrayReader;
use \Ddeboer\DataImport\Workflow;
use \Ddeboer\DataImport\Writer\ArrayWriter;
use \Liip\FunctionalTestBundle\Test\WebTestCase;
use \NS\ImportBundle\Entity\Column;
use \NS\ImportBundle\Entity\Map;
use \NS\ImportBundle\Entity\Result;
use \NS\ImportBundle\Filter\Duplicate;
use \NS\ImportBundle\Filter\DuplicateFilterFactory;
use \NS\ImportBundle\Filter\LinkerFilterFactory;
use \NS\ImportBundle\Filter\NotBlank;
use \NS\ImportBundle\Filter\NotBlankFilterFactory;
use \NS\ImportBundle\Services\ImportProcessor;
use \Symfony\Component\HttpFoundation\File\File;

/**
 * Description of ImportProcessorTest
 *
 * @author gnat
 */
class ImportProcessorTest extends WebTestCase
{
//
//    /**
//     * @expectedException InvalidArgumentException
//     */
//    public function testInvalidImportReader()
//    {
//        $file = new File(__DIR__ . '/../Fixtures/IBD-BadHeader.csv');
//
//        $import = new Result();
//        $import->setImportFile($file);
//        $import->setMap($this->getIbdMap($this->getIbdColumns()));
//
//        $processor = $this->getContainer()->get('ns_import.processor');
//        $processor->getReader($import);
//    }
//
//    /**
//     * @expectedException InvalidArgumentException
//     */
//    public function testOutOfOrderReader()
//    {
//        $file    = new File(__DIR__ . '/../Fixtures/IBD-BadHeader.csv');
//        $columns = array(
//            array(
//                'name'      => 'Col1',
//                'converter' => null,
//                'mapper'    => null,
//                'ignored'   => true,
//            ),
//            array(
//                'name'      => 'Col3',
//                'converter' => '',
//                'mapper'    => '',
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'Col2',
//                'converter' => null,
//                'mapper'    => '',
//                'ignored'   => false,
//            ),
//        );
//
//        $import = new Result();
//        $import->setImportFile($file);
//        $import->setMap($this->getIbdMap($columns));
//
//        $processor = $this->getContainer()->get('ns_import.processor');
//        $processor->getReader($import);
//    }
//
//    /**
//     * @expectedException InvalidArgumentException
//     */
//    public function testExtraColumnReader()
//    {
//        $file    = new File(__DIR__ . '/../Fixtures/IBD-BadHeader.csv');
//        $columns = array(
//            array(
//                'name'      => 'Col1',
//                'converter' => null,
//                'mapper'    => null,
//                'ignored'   => true,
//            ),
//            array(
//                'name'      => 'Col3',
//                'converter' => '',
//                'mapper'    => '',
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'Col2',
//                'converter' => null,
//                'mapper'    => '',
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'gender',
//                'converter' => '',
//                'mapper'    => '',
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'birthday',
//                'converter' => 'ns_import.converter.date.who',
//                'mapper'    => 'dob',
//                'ignored'   => false,
//            ),
//        );
//
//        $import = new Result();
//        $import->setImportFile($file);
//        $import->setMap($this->getIbdMap($columns));
//
//        $processor = $this->getContainer()->get('ns_import.processor');
//        $processor->getReader($import);
//    }
//
//    public function testMatchingColumnsReader()
//    {
//        $file    = new File(__DIR__ . '/../Fixtures/IBD-BadHeader.csv');
//        $columns = array(
//            array(
//                'name'      => 'Col1',
//                'converter' => null,
//                'mapper'    => null,
//                'ignored'   => true,
//            ),
//            array(
//                'name'      => 'Col2',
//                'converter' => '',
//                'mapper'    => '',
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'Col3',
//                'converter' => null,
//                'mapper'    => '',
//                'ignored'   => false,
//            ),
//        );
//
//        $import = new Result();
//        $import->setImportFile($file);
//        $import->setMap($this->getIbdMap($columns));
//
//        $processor = $this->getContainer()->get('ns_import.processor');
//        $reader    = $processor->getReader($import);
//        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);
//    }
//
//    /**
//     * @expectedException InvalidArgumentException
//     */
//    public function testGetDoctrineWriterWithoutClass()
//    {
//        $processor = $this->getContainer()->get('ns_import.processor');
//        $processor->getWriter();
//    }
//
//    public function testGetDoctrineWriter()
//    {
//        $entityMgr = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
//            ->disableOriginalConstructor()
//            ->getMock();
//
//        $mockContainer = $this->getMockBuilder('\Symfony\Component\DependencyInjection\ContainerInterface')
//            ->disableOriginalConstructor()
//            ->getMock();
//
//        $mockContainer->expects($this->once())
//            ->method('get')
//            ->with('doctrine.orm.entity_manager')
//            ->will($this->returnValue($entityMgr));
//
//
//        $processor = new ImportProcessor($mockContainer, new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
//        $processor->setDuplicate(new Duplicate());
//        $writer    = $processor->getWriter('NS\SentinelBundle\Entity\IBD');
//        $this->assertInstanceOf('\Ddeboer\DataImport\Writer\DoctrineWriter', $writer);
//        $writer2   = $processor->getWriter('NS\SentinelBundle\Entity\IBD');
//
//        $this->assertEquals($writer, $writer2);
//    }
//
////    public function testAddMappersWithDuplicate()
////    {
////        $user = $this->getContainer()
////            ->get('doctrine.orm.entity_manager')
////            ->getRepository('NSSentinelBundle:User')
////            ->findOneByEmail(array('email' => 'ca-full@noblet.ca'));
////
////        $this->loginAs($user, 'main_app');
////
////        $file   = new File(__DIR__ . '/../Fixtures/IBD.csv');
////        $import = new Result();
////        $import->setImportFile($file);
////        $import->setMap($this->getIbdMap($this->getIbdColumns()));
////
////        $processor = $this->getContainer()->get('ns_import.processor');
////        $reader    = $processor->getReader($import);
////        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);
////
////        $outputData = array();
////        $workflow   = new Workflow($reader);
////        $workflow->setSkipItemOnFailure(true);
////        $workflow->addWriter(new ArrayWriter($outputData));
////
////        $processor->addFilters($workflow, $import);
//////        $duplicate = $processor->getDuplicate();
////
////        $this->assertEquals(4, $workflow->getStepCount());
////        $this->assertCount(2,$workflow->getAfterConversionFilters());
//////        foreach ($workflow->getAfterConversionFilters() as $filter) {
//////            if ($filter instanceof Duplicate) {
//////                $this->assertEquals($duplicate, $filter);
//////                $this->assertCount(count($duplicate->toArray()), $filter->toArray());
//////            }
//////        }
//////        $this->assertCount(2, $workflow->getValueConverters());
//////        $this->assertCount(2, $workflow->getItemConverters());
////    }
//
////    public function testAddMappersWithoutDuplicate()
////    {
////        $user = $this->getContainer()
////            ->get('doctrine.orm.entity_manager')
////            ->getRepository('NSSentinelBundle:User')
////            ->findOneByEmail(array('email' => 'ca-full@noblet.ca'));
////
////        $this->loginAs($user, 'main_app');
////
////        $file = new File(__DIR__ . '/../Fixtures/IBD.csv');
////
////        $import = new Result();
////        $import->setImportFile($file);
////        $import->setMap($this->getIbdMap($this->getIbdColumns()));
////        $import->getMap()->setClass(null);
////
////        $processor = $this->getContainer()->get('ns_import.processor');
////        $reader    = $processor->getReader($import);
////        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);
////
////        $outputData = array();
////        $workflow   = new Workflow($reader);
////        $workflow->setSkipItemOnFailure(true);
////        $workflow->addWriter(new ArrayWriter($outputData));
////
////        $processor->addFilters($workflow, $import);
////        $this->assertEquals(0, $workflow->getStepCount());
////
//////        $this->assertCount(0, $workflow->getAfterConversionFilters());
//////        $this->assertCount(2, $workflow->getValueConverters());
//////        $this->assertCount(2, $workflow->getItemConverters());
////    }
//
//    public function testDuplicateFilterIsCalled()
//    {
//        $file    = new File(__DIR__ . '/../Fixtures/IBD-DuplicateRows.csv');
//        $columns = array(
//            array(
//                'name'      => 'Col1',
//                'converter' => null,
//                'mapper'    => 'col1',
//                'ignored'   => true,
//            ),
//            array(
//                'name'      => 'Col2',
//                'converter' => '',
//                'mapper'    => 'col2',
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'Col3',
//                'converter' => null,
//                'mapper'    => null,
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'Col4',
//                'converter' => null,
//                'mapper'    => null,
//                'ignored'   => true,
//            ),
//        );
//
//        $import = new Result();
//        $import->setImportFile($file);
//        $import->setMap($this->getIbdMap($columns));
//
//        $uniqueFields  = array('col1', 'col2');
//        $mockDuplicate = $this->getMockBuilder('NS\ImportBundle\Filter\Duplicate')
//            ->setMethods(array('filter', 'getFieldKey'))
//            ->getMock(array($uniqueFields));
//
//        $mockDuplicate
//            ->expects($this->at(0))
//            ->method('filter')
//            ->with(array('col1' => 1,'col2' => 2, 'Col3' => 3, 'Col1' => 1, 'Col2' => 2, 'Col4' => 4))
//            ->willReturn(true);
//
//        $mockDuplicate
//            ->expects($this->at(1))
//            ->method('filter')
//            ->with(array('col1' => 3, 'col2' => 3, 'Col3' => 4,'Col1'=>3,'Col2'=>3,'Col4'=>5))
//            ->willReturn(true);
//
//        $mockDuplicate
//            ->expects($this->at(2))
//            ->method('filter')
//            ->with(array('col1' => 1, 'col2' => 2, 'Col3' => 5,'Col1'=>1,'Col2'=>2,'Col4'=>6))
//            ->will($this->returnValue(FALSE));
//
//        $mockDuplicate
//            ->expects($this->at(3))
//            ->method('filter')
//            ->with(array('col1' => 4, 'col2' => 5, 'Col3' => 6,'Col1'=>4,'Col2'=>5,'Col4'=>7))
//            ->willReturn(true);
//
//        $processor = new ImportProcessor($this->getContainer(), new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
//        $processor->setDuplicate($mockDuplicate);
//        $processor->setNotBlank(new NotBlank('col1'));
//        $reader    = $processor->getReader($import);
//
//        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);
//        $this->assertCount(4, $reader);
//
//        $outputData = array();
//        // Create the workflow from the reader
//        $workflow   = new Workflow($reader);
//        $workflow->setSkipItemOnFailure(true);
//        $workflow->addWriter(new ArrayWriter($outputData));
//
//        $processor->addFilters($workflow, $import);
//
//        $workflow->process();
//        $this->assertCount(3, $outputData, print_r($outputData,true));
//    }
//
//    public function testDuplicates()
//    {
//        $file    = new File(__DIR__ . '/../Fixtures/IBD-DuplicateRows.csv');
//        $columns = array(
//            array(
//                'name'      => 'Col1',
//                'converter' => null,
//                'mapper'    => null,
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'Col2',
//                'converter' => null,
//                'mapper'    => 'col2',
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'Col3',
//                'converter' => null,
//                'mapper'    => null,
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'Col4',
//                'converter' => null,
//                'mapper'    => null,
//                'ignored'   => true,
//            ),
//        );
//
//        $import = new Result();
//        $import->setImportFile($file);
//        $import->setMap($this->getIbdMap($columns));
//
//        $uniqueFields = array('Col1', 'col2');
//        $duplicate    = new Duplicate($uniqueFields);
//
//        $processor = new ImportProcessor($this->getContainer(), new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
//        $processor->setDuplicate($duplicate);
//        $processor->setNotBlank(new NotBlank('Col1'));
//        $reader    = $processor->getReader($import);
//
//        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);
//        $this->assertCount(4, $reader);
//
//        $outputData = array();
//        $workflow   = new Workflow($reader);
//        $workflow->setSkipItemOnFailure(true);
//        $workflow->addWriter(new ArrayWriter($outputData));
//
//        $processor->addFilters($workflow, $import);
//
//        $workflow->process();
//
//        $this->assertCount(3, $outputData);
//        $this->assertCount(1, $duplicate->toArray());
//    }

    public function testBadDateFormat()
    {
        $file    = new File(__DIR__ . '/../Fixtures/IBD-BadDate.csv');
        $columns = array(
            array(
                'name'      => 'date',
                'converter' => 'ns_import.converter.date.who',
                'mapper'    => null,
                'ignored'   => false,
            ),
            array(
                'name'      => 'ignored',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => true,
            ),
        );

        $mockUser = $this->getMock('Symfony\Component\Security\Core\User\UserInterface');
        $import = new Result($mockUser);
        $import->setImportFile($file);
        $import->setMap($this->getIbdMap($columns));

        $duplicate = new Duplicate(array());
        $processor = new ImportProcessor($this->getContainer(), new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
        $processor->setDuplicate($duplicate);
        $processor->setNotBlank(new NotBlank('date'));
        $reader    = $processor->getReader($import);

        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\CsvReader', $reader);
        $this->assertCount(2, $reader);

        $outputData = array();
        $workflow   = new Workflow\StepAggregator($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter(new ArrayWriter($outputData));

        $processor->addFilters($workflow, $import);

        $result = $workflow->process();
//
//        $this->assertCount(0, $outputData);
//        $this->assertInstanceOf('\SplObjectStorage', $result->getExceptions());
//        $this->assertEquals(2, $result->getExceptions()->count());
    }

    public function testBlankFieldConversion()
    {
        $container = $this->getMockBuilder('\Symfony\Component\DependencyInjection\ContainerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $processor = new ImportProcessor($container, new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
        $processor->setNotBlank(new NotBlank("caseId"));
        $this->assertInstanceOf('NS\ImportBundle\Filter\NotBlank', $processor->getNotBlank());
//        $this->assertInstanceOf('\Ddeboer\DataImport\Filter\FilterInterface', $processor->getNotBlank());
        $this->assertTrue(is_array($processor->getNotBlank()->fields));
        $this->assertEquals(array('caseId'), $processor->getNotBlank()->fields);
    }

    public function testNotBlank()
    {
        $notBlankStr = new NotBlank("field");
        $this->assertEquals(array('field'), $notBlankStr->fields);

        $notBlankArr = new NotBlank(array("field"));
        $this->assertEquals(array('field'), $notBlankArr->fields);

        $this->assertTrue($notBlankArr->__invoke(array('field' => '1', 'something' => 2)));
        $this->assertFalse($notBlankArr->__invoke(array('field' => null, 'something' => 2)));
        $this->assertFalse($notBlankArr->__invoke(array('field' => '', 'something' => 2)));
    }

    public function testDefaultValues()
    {
        $processor = new ImportProcessor($this->getContainer(), new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
        $this->assertEquals($processor->getMaxExecutionTime(), '190');
        $this->assertEquals($processor->getMemoryLimit(), '1024M');

        $processor->setMaxExecutionTime(120);
        $processor->setMemoryLimit('1024M');
        $this->assertEquals($processor->getMaxExecutionTime(), '120');
        $this->assertEquals($processor->getMemoryLimit(), '1024M');
    }

    public function testReferenceLabDuplicateAndNotBlankFields()
    {
        $processor = new ImportProcessor($this->getContainer(), new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
        $mockUser  = $this->getMock('Symfony\Component\Security\Core\User\UserInterface');
        $import    = new Result($mockUser);
        $map       = new Map();
        $map->setClass('NS\SentinelBundle\Entity\IBD\ReferenceLab');
        $import->setMap($map);

        $processor->initializeDuplicateFilter($import);
        $this->assertNotNull($processor->getDuplicate());
        $this->assertEquals(array('getid' => 'caseFile'), $processor->getDuplicate()->getFields());

        $processor->initializeNotBlankFilter($import);
        $this->assertNotNull($processor->getNotBlank());
        $this->assertEquals(array('caseFile', 'labId'), $processor->getNotBlank()->fields);
    }

    /**
     * @group deep
     */
    public function testDeepArrayMap()
    {
        $columns = array(
            array(
                'name'      => 'Date Sample received-RRL',
                'converter' => 'ns_import.converter.date.year_month_day',
                'mapper'    => 'dateReceived',
                'ignored'   => true,
            ),
            array(
                'name'      => 'RRL lab #',
                'converter' => null,
                'mapper'    => 'labId',
                'ignored'   => false,
            ),
            array(
                'name'      => 'patient first name',
                'converter' => null,
                'mapper'    => 'caseFile.firstName',
                'ignored'   => false,
            ),
            array(
                'name'      => 'Site Code',
                'converter' => 'ns.sentinel.converter.site',
                'mapper'    => 'caseFile.site',
                'ignored'   => false,
            ),
            array(
                'name'      => 'case id',
                'converter' => null,
                'mapper'    => 'caseFile.caseId',
                'ignored'   => false,
            ),
        );

        $source = array(
            array('Date Sample received-RRL' => '2014/01/01', 'RRL lab #' => '13314',
                'Site Code' => 'ALBCHLD', 'case id' => '12', 'patient first name' => 'Fname 1'),
            array('Date Sample received-RRL' => '2014/06/10', 'RRL lab #' => '1314',
                'Site Code' => 'ALBCHLD', 'case id' => '14', 'patient first name' => 'Fname 2'),
            array('Date Sample received-RRL' => '2014/07/18', 'RRL lab #' => '12345',
                'Site Code' => 'ALBCHLD', 'case id' => '15', 'patient first name' => 'Fname 3'),
            array('Date Sample received-RRL' => '2014/09/15', 'RRL lab #' => '54321',
                'Site Code' => 'ALBCHLD', 'case id' => '16', 'patient first name' => 'Fname 4'),
        );

        $processor = $this->getContainer()->get('ns_import.processor');
        $reader    = new ArrayReader($source);
        $mockUser  = $this->getMock('Symfony\Component\Security\Core\User\UserInterface');
        $import    = new Result($mockUser);
        $import->setMap($this->getReferenceLabMap($columns));

        $converters = $import->getConverters();
        $this->assertCount(2,$converters);
//        $this->assertEquals('Site',end($converters)->getName());

        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ArrayReader', $reader);

        $outputData = array();
        $workflow   = new Workflow\StepAggregator($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter(new ArrayWriter($outputData));

        $processor->addFilters($workflow, $import);

        $res = $workflow->process();
        $this->assertInstanceOf("Ddeboer\DataImport\Result", $res);
        if ($res->getErrorCount() > 0) {
            $exceptions = $res->getExceptions();
            $this->assertInstanceOf('\SplObjectStorage', $exceptions);
            $this->assertEquals($res->getErrorCount(),$exceptions->count());
            $object = $exceptions->current();
            $this->assertInstanceOf('\Exception', $object);
            if($exceptions->valid()){
//                $object = $exceptions->current();
                $this->assertInstanceOf('\Exception', $object);
                $this->fail($object->getMessage());
            }
            $this->fail('Got here!?');
        }

        $this->assertCount(4, $outputData, sprintf("Didn't receive proper output - Error count: %d", $res->getErrorCount()));
        $this->assertInstanceOf('NS\SentinelBundle\Entity\Site', $outputData[0]['caseFile']['site']);
//        $this->fail(print_r(array_keys($outputData[0]['caseFile']),true));
//        $this->fail($outputData[0]['caseFile']['site']->getName());
    }

//    public function testSiteLabConverter()
//    {
//        $columns = array(
//            array(
//                'name'      => 'site_CODE',
//                'converter' => 'ns.sentinel.converter.site',
//                'mapper'    => 'site',
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'case_id',
//                'converter' => null,
//                'mapper'    => 'caseId',
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'firstName',
//                'converter' => null,
//                'mapper'    => null,
//                'ignored'   => false,
//            ),
//            array(
//                'name'      => 'csf Date',
//                'converter' => 'ns_import.converter.date.timestamp',
//                'mapper'    => 'siteLab.csfDateTime',
//                'ignored'   => false,
//            ),
//        );
//        $file    = new File(__DIR__ . '/../Fixtures/IBD-CasePlusSiteLab.csv');
//
//        $import = new Result();
//        $import->setImportFile($file);
//        $import->setMap($this->getIbdMap($columns));
//
//        $processor = $this->getContainer()->get('ns_import.processor');
//        $result    = $processor->process($import);
//        if ($result->getErrorCount() > 0) {
//            $exceptions = $result->getExceptions();
//            $this->fail($exceptions[0]->getMessage());
//        }
//        $this->assertEquals(2, $result->getSuccessCount());
//    }

    public function getReferenceLabMap(array $columns)
    {
        return $this->getMap('NS\SentinelBundle\Entity\IBD\ReferenceLab', 'IBD Reference Lab', $columns);
    }

    public function getIbdMap(array $columns)
    {
        return $this->getMap('NS\SentinelBundle\Entity\IBD', 'IBD Clinical', $columns);
    }

    public function getMap($class, $name, $columns)
    {
        $map = new Map();
        $map->setName($name);
        $map->setClass($class);

        foreach ($columns as $index => $colArray) {
            $column = new Column();
            $column->setOrder($index);
            $column->setName($colArray['name']);
            $column->setConverter($colArray['converter']);
            $column->setMapper($colArray['mapper']);
            $column->setIgnored($colArray['ignored']);
            $map->addColumn($column);
        }

        return $map;
    }

    public function getIbdColumns()
    {
        return array(
            array(
                'name'      => 'ISO3_code',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => true,
            ),
            array(
                'name'      => 'site_code',
                'converter' => 'ns.sentinel.converter.site',
                'mapper'    => 'site',
                'ignored'   => false,
            ),
            array(
                'name'      => 'case_ID',
                'converter' => null,
                'mapper'    => 'caseId',
                'ignored'   => false,
            ),
            array(
                'name'      => 'gender',
                'converter' => 'ns.sentinel.converter.gender',
                'mapper'    => 'gender',
                'ignored'   => false,
            ),
        );
    }
}
