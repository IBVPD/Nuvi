<?php

namespace NS\ImportBundle\Tests\Filter;

/**
 * Description of DuplicateTest
 *
 * @author gnat
 */
class DuplicateTest extends \PHPUnit_Framework_TestCase
{
    public function testDuplicateTextOnlyItemIsDetected()
    {
        $uniqueFields = array('site', 'caseId');
        $params       = array('site' => 'sitecode', 'caseId' => '12223');
        $duplicate    = new \NS\ImportBundle\Filter\Duplicate($uniqueFields);
        $this->assertTrue($duplicate->filter($params), "First set of params is not a duplicate");
        $this->assertFalse($duplicate->filter($params), "Second set of params is a duplicate");
    }

    public function testDuplicateObjectItemIsDetected()
    {
        $site = new \NS\SentinelBundle\Entity\Site();
        $site->setCode('MY-CODE');
        $site->setName("My Code Site Tester");

        $uniqueFields = array('getcode' => 'site', 'caseId');
        $params       = array('site' => $site, 'caseId' => '12223');
        $duplicate    = new \NS\ImportBundle\Filter\Duplicate($uniqueFields);
        $this->assertTrue($duplicate->filter($params), "First set of params is not a duplicate");
        $this->assertFalse($duplicate->filter($params), "Second set of params is a duplicate");
        $duplicateArray = $duplicate->toArray();

        $this->assertCount(1, $duplicateArray);
        $this->assertArrayHasKey(0, $duplicateArray);
        $this->assertEquals('MY-CODE-12223', $duplicateArray[0], "The duplicate array has the proper duplicate key");
    }

}
