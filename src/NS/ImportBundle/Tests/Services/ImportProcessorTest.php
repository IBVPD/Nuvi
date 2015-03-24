<?php

namespace NS\ImportBundle\Tests\Services;

use Ddeboer\DataImport\Reader\ArrayReader;
use Ddeboer\DataImport\Writer\ArrayWriter;
use InvalidArgumentException;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use NS\ImportBundle\Entity\Column;
use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Entity\Map;
use NS\ImportBundle\Filter\Duplicate;
use NS\ImportBundle\Filter\DuplicateFilterFactory;
use NS\ImportBundle\Filter\LinkerFilterFactory;
use NS\ImportBundle\Filter\NotBlank;
use NS\ImportBundle\Filter\NotBlankFilterFactory;
use NS\ImportBundle\Services\ImportProcessor;
use NS\ImportBundle\Workflow\Workflow;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Description of ImportProcessorTest
 *
 * @author gnat
 */
class ImportProcessorTest extends WebTestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidImportReader()
    {
        $file = new File(__DIR__ . '/../Fixtures/IBD-BadHeader.csv');

        $import = new Import();
        $import->setFile($file);
        $import->setMap($this->getIbdMap($this->getIbdColumns()));

        $processor = $this->getContainer()->get('ns_import.processor');
        $processor->getReader($import);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testOutOfOrderReader()
    {
        $file    = new File(__DIR__ . '/../Fixtures/IBD-BadHeader.csv');
        $columns = array(
            array(
                'name'      => 'Col1',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => true,
            ),
            array(
                'name'      => 'Col3',
                'converter' => '',
                'mapper'    => '',
                'ignored'   => false,
            ),
            array(
                'name'      => 'Col2',
                'converter' => null,
                'mapper'    => '',
                'ignored'   => false,
            ),
        );

        $import = new Import();
        $import->setFile($file);
        $import->setMap($this->getIbdMap($columns));

        $processor = $this->getContainer()->get('ns_import.processor');
        $processor->getReader($import);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExtraColumnReader()
    {
        $file    = new File(__DIR__ . '/../Fixtures/IBD-BadHeader.csv');
        $columns = array(
            array(
                'name'      => 'Col1',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => true,
            ),
            array(
                'name'      => 'Col3',
                'converter' => '',
                'mapper'    => '',
                'ignored'   => false,
            ),
            array(
                'name'      => 'Col2',
                'converter' => null,
                'mapper'    => '',
                'ignored'   => false,
            ),
            array(
                'name'      => 'gender',
                'converter' => '',
                'mapper'    => '',
                'ignored'   => false,
            ),
            array(
                'name'      => 'birthday',
                'converter' => 'ns_import.converter.date.who',
                'mapper'    => 'dob',
                'ignored'   => false,
            ),
        );

        $import = new Import();
        $import->setFile($file);
        $import->setMap($this->getIbdMap($columns));

        $processor = $this->getContainer()->get('ns_import.processor');
        $processor->getReader($import);
    }

    public function testMatchingColumnsReader()
    {
        $file    = new File(__DIR__ . '/../Fixtures/IBD-BadHeader.csv');
        $columns = array(
            array(
                'name'      => 'Col1',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => true,
            ),
            array(
                'name'      => 'Col2',
                'converter' => '',
                'mapper'    => '',
                'ignored'   => false,
            ),
            array(
                'name'      => 'Col3',
                'converter' => null,
                'mapper'    => '',
                'ignored'   => false,
            ),
        );

        $import = new Import();
        $import->setFile($file);
        $import->setMap($this->getIbdMap($columns));

        $processor = $this->getContainer()->get('ns_import.processor');
        $reader    = $processor->getReader($import);
        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetDoctrineWriterWithoutClass()
    {
        $processor = $this->getContainer()->get('ns_import.processor');
        $processor->getWriter();
    }

    public function testGetDoctrineWriter()
    {
        $entityMgr = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockContainer = $this->getMockBuilder('\Symfony\Component\DependencyInjection\ContainerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mockContainer->expects($this->once())
            ->method('get')
            ->with('ns.model_manager')
            ->will($this->returnValue($entityMgr));


        $processor = new ImportProcessor($mockContainer, new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
        $processor->setDuplicate(new Duplicate());
        $writer       = $processor->getWriter('NS\SentinelBundle\Entity\IBD');
        $this->assertInstanceOf('\Ddeboer\DataImport\Writer\DoctrineWriter', $writer);
        $writer2      = $processor->getWriter('NS\SentinelBundle\Entity\IBD');

        $this->assertEquals($writer, $writer2);
    }

    public function testAddMappersWithDuplicate()
    {
        $user = $this->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('NSSentinelBundle:User')
            ->findOneByEmail(array('email' => 'ca-full@noblet.ca'));

        $this->loginAs($user, 'main_app');

        $file   = new File(__DIR__ . '/../Fixtures/IBD.csv');
        $import = new Import();
        $import->setFile($file);
        $import->setMap($this->getIbdMap($this->getIbdColumns()));

        $processor = $this->getContainer()->get('ns_import.processor');
        $reader    = $processor->getReader($import);
        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);

        $outputData = array();
        $workflow   = new Workflow($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter(new ArrayWriter($outputData));

        $processor->addFilters($workflow, $import);
        $duplicate = $processor->getDuplicate();

        $this->assertCount(2, $workflow->getAfterConversionFilters());

        foreach ($workflow->getAfterConversionFilters() as $filter)
        {
            if ($filter instanceof Duplicate)
            {
                $this->assertEquals($duplicate, $filter);
                $this->assertCount(count($duplicate->toArray()), $filter->toArray());
            }
        }
        $this->assertCount(2, $workflow->getValueConverters());
        $this->assertCount(2, $workflow->getItemConverters());
    }

    public function testAddMappersWithoutDuplicate()
    {
        $user = $this->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('NSSentinelBundle:User')
            ->findOneByEmail(array('email' => 'ca-full@noblet.ca'));

        $this->loginAs($user, 'main_app');

        $file = new File(__DIR__ . '/../Fixtures/IBD.csv');

        $import = new Import();
        $import->setFile($file);
        $import->setMap($this->getIbdMap($this->getIbdColumns()));
        $import->getMap()->setClass(null);

        $processor = $this->getContainer()->get('ns_import.processor');
        $reader    = $processor->getReader($import);
        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);

        $outputData = array();
        $workflow   = new Workflow($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter(new ArrayWriter($outputData));

        $processor->addFilters($workflow, $import);

        $this->assertCount(0, $workflow->getAfterConversionFilters());
        $this->assertCount(2, $workflow->getValueConverters());
        $this->assertCount(2, $workflow->getItemConverters());
    }

    public function testDuplicateFilterIsCalled()
    {
        $file    = new File(__DIR__ . '/../Fixtures/IBD-DuplicateRows.csv');
        $columns = array(
            array(
                'name'      => 'Col1',
                'converter' => null,
                'mapper'    => 'col1',
                'ignored'   => true,
            ),
            array(
                'name'      => 'Col2',
                'converter' => '',
                'mapper'    => 'col2',
                'ignored'   => false,
            ),
            array(
                'name'      => 'Col3',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => false,
            ),
            array(
                'name'      => 'Col4',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => true,
            ),
        );

        $import = new Import();
        $import->setFile($file);
        $import->setMap($this->getIbdMap($columns));

        $uniqueFields  = array('col1', 'col2');
        $mockDuplicate = $this->getMockBuilder('NS\ImportBundle\Filter\Duplicate')
            ->setMethods(array('filter', 'getFieldKey'))
            ->getMock(array($uniqueFields));

        $mockDuplicate
            ->expects($this->at(0))
            ->method('filter')
            ->with(array('col1' => 1, 'col2' => 2, 'Col3' => 3))
            ->willReturn(true);

        $mockDuplicate
            ->expects($this->at(1))
            ->method('filter')
            ->with(array('col1' => 3, 'col2' => 3, 'Col3' => 4))
            ->willReturn(true);

        $mockDuplicate
            ->expects($this->at(2))
            ->method('filter')
            ->with(array('col1' => 1, 'col2' => 2, 'Col3' => 5))
            ->willReturn(false);

        $mockDuplicate
            ->expects($this->at(3))
            ->method('filter')
            ->with(array('col1' => 4, 'col2' => 5, 'Col3' => 6))
            ->willReturn(true);

        $processor = new ImportProcessor($this->getContainer(), new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
        $processor->setDuplicate($mockDuplicate);
        $processor->setNotBlank(new NotBlank('col1'));
        $reader    = $processor->getReader($import);

        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);
        $this->assertCount(4, $reader);

        $outputData = array();
        // Create the workflow from the reader
        $workflow   = new Workflow($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter(new ArrayWriter($outputData));

        $processor->addFilters($workflow, $import);

        $workflow->process();
        $this->assertCount(3, $outputData);
    }

    public function testDuplicates()
    {
        $file    = new File(__DIR__ . '/../Fixtures/IBD-DuplicateRows.csv');
        $columns = array(
            array(
                'name'      => 'Col1',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => false,
            ),
            array(
                'name'      => 'Col2',
                'converter' => null,
                'mapper'    => 'col2',
                'ignored'   => false,
            ),
            array(
                'name'      => 'Col3',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => false,
            ),
            array(
                'name'      => 'Col4',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => true,
            ),
        );

        $import = new Import();
        $import->setFile($file);
        $import->setMap($this->getIbdMap($columns));

        $uniqueFields = array('Col1', 'col2');
        $duplicate    = new Duplicate($uniqueFields);

        $processor = new ImportProcessor($this->getContainer(), new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
        $processor->setDuplicate($duplicate);
        $processor->setNotBlank(new NotBlank('Col1'));
        $reader    = $processor->getReader($import);

        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);
        $this->assertCount(4, $reader);

        $outputData = array();
        $workflow   = new Workflow($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter(new ArrayWriter($outputData));

        $processor->addFilters($workflow, $import);

        $workflow->process();

        $this->assertCount(3, $outputData);
        $this->assertCount(1, $duplicate->toArray());
    }

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

        $import = new Import();
        $import->setFile($file);
        $import->setMap($this->getIbdMap($columns));

        $duplicate = new Duplicate(array());
        $processor = new ImportProcessor($this->getContainer(), new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
        $processor->setDuplicate($duplicate);
        $processor->setNotBlank(new NotBlank('date'));
        $reader    = $processor->getReader($import);

        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);
        $this->assertCount(2, $reader);

        $outputData = array();
        $workflow   = new Workflow($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter(new ArrayWriter($outputData));

        $processor->addFilters($workflow, $import);

        $result = $workflow->process();

        $this->assertCount(0, $outputData);
        $this->assertCount(0, $duplicate->toArray());
        $this->assertCount(2, $result->getExceptions());
    }

    public function testBlankFieldConversion()
    {
        $container = $this->getMockBuilder('\Symfony\Component\DependencyInjection\ContainerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $processor = new ImportProcessor($container, new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
        $processor->setNotBlank(new NotBlank("caseId"));
        $this->assertInstanceOf('NS\ImportBundle\Filter\NotBlank', $processor->getNotBlank());
        $this->assertInstanceOf('\Ddeboer\DataImport\Filter\FilterInterface', $processor->getNotBlank());
        $this->assertTrue(is_array($processor->getNotBlank()->fields));
        $this->assertEquals(array('caseId'), $processor->getNotBlank()->fields);
    }

    public function testNotBlank()
    {
        $notBlankStr = new NotBlank("field");
        $this->assertEquals(array('field'), $notBlankStr->fields);

        $notBlankArr = new NotBlank(array("field"));
        $this->assertEquals(array('field'), $notBlankArr->fields);

        $this->assertTrue($notBlankArr->filter(array('field' => '1', 'something' => 2)));
        $this->assertFalse($notBlankArr->filter(array('field' => null, 'something' => 2)));
        $this->assertFalse($notBlankArr->filter(array('field' => '', 'something' => 2)));
    }

    public function testDefaultValues()
    {
        $processor = new ImportProcessor($this->getContainer(), new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
        $this->assertEquals($processor->getMaxExecutionTime(), '90');
        $this->assertEquals($processor->getMemoryLimit(), '512M');

        $processor->setMaxExecutionTime(120);
        $processor->setMemoryLimit('1024M');
        $this->assertEquals($processor->getMaxExecutionTime(), '120');
        $this->assertEquals($processor->getMemoryLimit(), '1024M');
    }

    public function testReferenceLabDuplicateAndNotBlankFields()
    {
        $processor = new ImportProcessor($this->getContainer(), new DuplicateFilterFactory(), new NotBlankFilterFactory(), new LinkerFilterFactory(array()));
        $import    = new Import();
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
            array('Date Sample received-RRL'=>'2014/01/01','RRL lab #'=>'13314','Site Code'=>'ALBCHLD','case id'=>'12','patient first name'=>'Fname 1'),
            array('Date Sample received-RRL'=>'2014/06/10','RRL lab #'=>'1314','Site Code'=>'ALBCHLD','case id'=>'14','patient first name'=>'Fname 2'),
            array('Date Sample received-RRL'=>'2014/07/18','RRL lab #'=>'12345','Site Code'=>'ALBCHLD','case id'=>'15','patient first name'=>'Fname 3'),
            array('Date Sample received-RRL'=>'2014/09/15','RRL lab #'=>'54321','Site Code'=>'ALBCHLD','case id'=>'16','patient first name'=>'Fname 4'),
        );

        $processor = $this->getContainer()->get('ns_import.processor');
        $reader    = new ArrayReader($source);
        $import    = new Import();
        $import->setMap($this->getReferenceLabMap($columns));

        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\ReaderInterface', $reader);

        $outputData = array();
        $workflow   = new Workflow($reader);
        $workflow->setSkipItemOnFailure(true);
        $workflow->addWriter(new ArrayWriter($outputData));

        $processor->addFilters($workflow, $import);

        $res = $workflow->process();
        $this->assertInstanceOf("Ddeboer\DataImport\Result", $res);
        if($res->getErrorCount() > 0)
        {
            $exceptions = $res->getExceptions();
            $this->fail($exceptions[0]->getMessage());
        }

        $this->assertCount(4, $outputData, sprintf("Didn't receive proper output - Error count: %d",$res->getErrorCount()));
        $this->assertInstanceOf('NS\SentinelBundle\Entity\Site', $outputData[0]['caseFile']['site']);
//        $this->fail(print_r(array_keys($outputData[0]['caseFile']),true));
//        $this->fail($outputData[0]['caseFile']['site']->getName());
    }

    public function testSiteLabConverter()
    {
        $columns = array(
            array(
                'name'      => 'site_CODE',
                'converter' => 'ns.sentinel.converter.site',
                'mapper'    => 'site',
                'ignored'   => false,
            ),
            array(
                'name'      => 'case_id',
                'converter' => null,
                'mapper'    => 'caseId',
                'ignored'   => false,
            ),
            array(
                'name'      => 'firstName',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => false,
            ),
            array(
                'name'      => 'csf Date',
                'converter' => 'ns_import.converter.date.timestamp',
                'mapper'    => 'siteLab.csfDateTime',
                'ignored'   => false,
            ),
        );
        $file    = new File(__DIR__ . '/../Fixtures/IBD-CasePlusSiteLab.csv');

        $import = new Import();
        $import->setFile($file);
        $import->setMap($this->getIbdMap($columns));

        $processor = $this->getContainer()->get('ns_import.processor');
        $result = $processor->process($import);
        if($result->getErrorCount() > 0)
        {
            $exceptions = $result->getExceptions();
            $this->fail($exceptions[0]->getMessage());
        }
        $this->assertEquals(2, $result->getSuccessCount());
    }

    public function getReferenceLabMap(array $columns)
    {
        return $this->getMap('NS\SentinelBundle\Entity\IBD\ReferenceLab','IBD Reference Lab',$columns);
    }

    public function getIbdMap(array $columns)
    {
        return $this->getMap('NS\SentinelBundle\Entity\IBD','IBD Clinical',$columns);
    }

    public function getMap($class,$name,$columns)
    {
        $map = new Map();
        $map->setName($name);
        $map->setClass($class);

        foreach ($columns as $index => $colArray)
        {
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
    /*
      +----+--------------+---------+------------------------------+-----------------+
      | id | name         | version | class                        | duplicateFields |
      +----+--------------+---------+------------------------------+-----------------+
      |  1 | IBD Clinical | 1       | NS\SentinelBundle\Entity\IBD | N;              |
      +----+--------------+---------+------------------------------+-----------------+

      | id | map_id | name                 | orderCol | converter                                     | mapper             | ignored   |
      +----+--------+----------------------+----------+-----------------------------------------------+--------------------+-----------+
      |  1 |      1 | Region               |        0 | NULL                                          | NULL               |         1 |
      |  2 |      1 | ISO3_code            |        1 | NULL                                          | NULL               |         1 |
      |  3 |      1 | site_code            |        2 | ns.sentinel.converter.site                    | site               |         0 |
      |  4 |      1 | case_ID              |        3 | NULL                                          | caseId             |         0 |
      |  5 |      1 | gender               |        4 | ns.sentinel.converter.gender                  | gender             |         0 |
      |  6 |      1 | birthdate            |        5 | ns_import.converter.date.who                  | dob                |         0 |
      |  7 |      1 | age_months           |        6 | NULL                                          | NULL               |         1 |
      |  8 |      1 | case_district        |        7 | NULL                                          | district           |         0 |
      |  9 |      1 | adm_date             |        8 | ns_import.converter.date.who                  | admDate            |         0 |
      | 10 |      1 | onset_date           |        9 | ns_import.converter.date.who                  | onsetDate          |         0 |
      | 11 |      1 | adm_dx               |       10 | ns.sentinel.converter.diagnosis               | admDx              |         0 |
      | 12 |      1 | adm_dx_other         |       11 | NULL                                          | admDxOther         |         0 |
      | 13 |      1 | antibiotics          |       12 | ns.sentinel.converter.triple_choice           | antibiotics        |         0 |
      | 14 |      1 | men_seizures         |       13 | ns.sentinel.converter.triple_choice           | menSeizures        |         0 |
      | 15 |      1 | men_fever            |       14 | ns.sentinel.converter.triple_choice           | menFever           |         0 |
      | 16 |      1 | men_alt_conscious    |       15 | ns.sentinel.converter.triple_choice           | menAltConscious    |         0 |
      | 17 |      1 | men_inability_feed   |       16 | ns.sentinel.converter.triple_choice           | menInabilityFeed   |         0 |
      | 18 |      1 | men_neck_stiff       |       17 | ns.sentinel.converter.triple_choice           | menNeckStiff       |         0 |
      | 19 |      1 | men_rash             |       18 | ns.sentinel.converter.triple_choice           | menRash            |         0 |
      | 20 |      1 | men_fontanelle_bulge |       19 | ns.sentinel.converter.triple_choice           | menFontanelleBulge |         0 |
      | 21 |      1 | men_lethargy         |       20 | ns.sentinel.converter.triple_choice           | menLethargy        |         0 |
      | 22 |      1 | pneu_diff_breathe    |       21 | ns.sentinel.converter.triple_choice           | pneuDiffBreathe    |         0 |
      | 23 |      1 | pneu_chest_indraw    |       22 | ns.sentinel.converter.triple_choice           | pneuChestIndraw    |         0 |
      | 24 |      1 | pneu_cough           |       23 | ns.sentinel.converter.triple_choice           | pneuCough          |         0 |
      | 25 |      1 | pneu_cyanosis        |       24 | ns.sentinel.converter.triple_choice           | pneuCyanosis       |         0 |
      | 26 |      1 | pneu_stridor         |       25 | ns.sentinel.converter.triple_choice           | pneuStridor        |         0 |
      | 27 |      1 | pneu_resp_rate       |       26 | NULL                                          | pneuRespRate       |         0 |
      | 28 |      1 | pneu_vomit           |       27 | ns.sentinel.converter.triple_choice           | pneuVomit          |         0 |
      | 29 |      1 | pneu_hypothermia     |       28 | ns.sentinel.converter.triple_choice           | pneuHypothermia    |         0 |
      | 30 |      1 | pneu_malnutrition    |       29 | ns.sentinel.converter.triple_choice           | pneuMalnutrition   |         0 |
      | 31 |      1 | hib_received         |       30 | ns.sentinel.converter.vaccinationreceived     | hibReceived        |         0 |
      | 32 |      1 | hib_doses            |       31 | ns.sentinel.converter.fourdoses               | hibDoses           |         0 |
      | 33 |      1 | PCV_received         |       32 | ns.sentinel.converter.vaccinationreceived     | pcvReceived        |         0 |
      | 34 |      1 | PCV_doses            |       33 | ns.sentinel.converter.threedoses              | pcvDoses           |         0 |
      | 35 |      1 | Mening_received      |       34 | ns.sentinel.converter.vaccinationreceived     | meningReceived     |         0 |
      | 36 |      1 | Mening_type          |       35 | ns.sentinel.converter.meningvaccinationtype   | meningType         |         0 |
      | 37 |      1 | Mening_date          |       36 | NULL                                          | NULL               |         1 |
      | 38 |      1 | CSF_collected        |       37 | ns.sentinel.converter.triple_choice           | csfCollected       |         0 |
      | 39 |      1 | CSF_ID               |       38 | NULL                                          | NULL               |         1 |
      | 40 |      1 | CSF_collect_date     |       39 | NULL                                          | NULL               |         1 |
      | 41 |      1 | CSF_collect_time     |       40 | NULL                                          | NULL               |         1 |
      | 42 |      1 | CSF_appearance       |       41 | ns.sentinel.converter.csfappearance           | csfAppearance      |         0 |
      | 43 |      1 | CSF_lab_date         |       42 | NULL                                          | NULL               |         1 |
      | 44 |      1 | CSF_lab_time         |       43 | NULL                                          | NULL               |         1 |
      | 45 |      1 | blood_collected      |       44 | ns.sentinel.converter.triple_choice           | bloodCollected     |         0 |
      | 46 |      1 | blood_ID             |       45 | NULL                                          | NULL               |         1 |
      | 47 |      1 | CSF_WCC              |       46 | NULL                                          | NULL               |         1 |
      | 48 |      1 | CSF_glucose          |       47 | NULL                                          | NULL               |         1 |
      | 49 |      1 | CSF_protein          |       48 | NULL                                          | NULL               |         1 |
      | 50 |      1 | CSF_cult_done        |       49 | NULL                                          | NULL               |         1 |
      | 51 |      1 | CSF_gram_done        |       50 | NULL                                          | NULL               |         1 |
      | 52 |      1 | CSF_binax_done       |       51 | NULL                                          | NULL               |         1 |
      | 53 |      1 | CSF_LAT_done         |       52 | NULL                                          | NULL               |         1 |
      | 54 |      1 | CSF_PCR_done         |       53 | NULL                                          | NULL               |         1 |
      | 55 |      1 | CSF_cult_result      |       54 | NULL                                          | NULL               |         1 |
      | 56 |      1 | CSF_cult_other       |       55 | NULL                                          | NULL               |         1 |
      | 57 |      1 | CSF_gram_stain       |       56 | NULL                                          | NULL               |         1 |
      | 58 |      1 | CSF_gram_result      |       57 | NULL                                          | NULL               |         1 |
      | 59 |      1 | CSF_gram_other       |       58 | NULL                                          | NULL               |         1 |
      | 60 |      1 | CSF_binax_result     |       59 | NULL                                          | NULL               |         1 |
      | 61 |      1 | CSF_LAT_result       |       60 | NULL                                          | NULL               |         1 |
      | 62 |      1 | CSF_LAT_other        |       61 | NULL                                          | NULL               |         1 |
      | 63 |      1 | CSF_PCR_result       |       62 | NULL                                          | NULL               |         1 |
      | 64 |      1 | CSF_PCR_other        |       63 | NULL                                          | NULL               |         1 |
      | 65 |      1 | RRL_CSF_date         |       64 | NULL                                          | NULL               |         1 |
      | 66 |      1 | RRL_isol_CSF_date    |       65 | NULL                                          | NULL               |         1 |
      | 67 |      1 | RRL_isol_blood_date  |       66 | NULL                                          | NULL               |         1 |
      | 68 |      1 | RRL_broth_date       |       67 | NULL                                          | NULL               |         1 |
      | 69 |      1 | CSF_store            |       68 | NULL                                          | NULL               |         1 |
      | 70 |      1 | isol_store           |       69 | NULL                                          | NULL               |         1 |
      | 71 |      1 | RRL_name             |       70 | NULL                                          | NULL               |         1 |
      | 72 |      1 | SPN_serotype         |       71 | NULL                                          | NULL               |         1 |
      | 73 |      1 | HI_serotype          |       72 | NULL                                          | NULL               |         1 |
      | 74 |      1 | NM_serogroup         |       73 | NULL                                          | NULL               |         1 |
      | 75 |      1 | blood_cult_done      |       74 | NULL                                          | NULL               |         1 |
      | 76 |      1 | blood_gram_done      |       75 | NULL                                          | NULL               |         1 |
      | 77 |      1 | blood_PCR_done       |       76 | NULL                                          | NULL               |         1 |
      | 78 |      1 | other_cult_done      |       77 | NULL                                          | NULL               |         1 |
      | 79 |      1 | other_test_done      |       78 | NULL                                          | NULL               |         1 |
      | 80 |      1 | other_test           |       79 | NULL                                          | NULL               |         1 |
      | 81 |      1 | blood_cult_result    |       80 | NULL                                          | NULL               |         1 |
      | 82 |      1 | blood_cult_other     |       81 | NULL                                          | NULL               |         1 |
      | 83 |      1 | blood_gram_stain     |       82 | NULL                                          | NULL               |         1 |
      | 84 |      1 | blood_gram_result    |       83 | NULL                                          | NULL               |         1 |
      | 85 |      1 | blood_gram_other     |       84 | NULL                                          | NULL               |         1 |
      | 86 |      1 | blood_PCR_result     |       85 | NULL                                          | NULL               |         1 |
      | 87 |      1 | blood_PCR_other      |       86 | NULL                                          | NULL               |         1 |
      | 88 |      1 | other_cult_result    |       87 | NULL                                          | NULL               |         1 |
      | 89 |      1 | other_cult_other     |       88 | NULL                                          | NULL               |         1 |
      | 90 |      1 | other_test_result    |       89 | NULL                                          | NULL               |         1 |
      | 91 |      1 | other_test_other     |       90 | NULL                                          | NULL               |         1 |
      | 92 |      1 | CXR_done             |       91 | ns.sentinel.converter.triple_choice           | cxrDone            |         0 |
      | 93 |      1 | CXR_result           |       92 | ns.sentinel.converter.cxrresult               | cxrResult          |         0 |
      | 94 |      1 | disch_outcome        |       93 | ns.sentinel.converter.dischargeoutcome        | dischOutcome       |         0 |
      | 95 |      1 | disch_dx             |       94 | ns.sentinel.converter.diagnosis               | dischDx            |         0 |
      | 96 |      1 | disch_dx_other       |       95 | NULL                                          | dischDxOther       |         0 |
      | 97 |      1 | disch_class          |       96 | ns.sentinel.converter.dischargeclassification | dischClass         |         0 |
      | 98 |      1 | disch_class_other    |       97 | NULL                                          | NULL               |         1 |
      | 99 |      1 | Comment              |       98 | NULL                                          | comment            |         0 |
     */
}