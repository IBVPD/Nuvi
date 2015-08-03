<?php

namespace NS\ImportBundle\Tests\Filter;

/**
 * Description of DuplicateTest
 *
 * @author gnat
 */
class DuplicateTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFieldKeyIsLowerCase()
    {
        $uniqueFields = array('site', 'caseId');
        $paramsOne    = array('site' => 'sitecode', 'caseId' => '12223');
        $paramsTwo    = array('site' => 'sIteCode', 'caseId' => '12223');

        $duplicate    = new \NS\ImportBundle\Filter\Duplicate($uniqueFields);

        $this->assertFalse($duplicate->hasMessage(), "There is no message ".$duplicate->getMessage());

        $this->assertTrue($duplicate->__invoke($paramsOne), "First set of params is not a duplicate");
        $this->assertFalse($duplicate->hasMessage(), "There is no message ".$duplicate->getMessage());

        $this->assertFalse($duplicate->__invoke($paramsTwo), "Second set of params is a duplicate");
        $this->assertTrue($duplicate->hasMessage(), "There is a message");
        $this->assertEquals('Duplicate row detected with key \'sitecode_12223\'',$duplicate->getMessage());
    }

    public function testDuplicateTextOnlyItemIsDetected()
    {
        $uniqueFields = array('site', 'caseId');
        $params       = array('site' => 'sitecode', 'caseId' => '12223');
        $duplicate    = new \NS\ImportBundle\Filter\Duplicate($uniqueFields);
        $this->assertTrue($duplicate->__invoke($params), "First set of params is not a duplicate");
        $this->assertFalse($duplicate->hasMessage(), "There is no message");
        $this->assertFalse($duplicate->__invoke($params), "Second set of params is a duplicate");
        $this->assertTrue($duplicate->hasMessage(), "There is a message");
    }

    public function testDuplicateObjectItemIsDetected()
    {
        $site = new \NS\SentinelBundle\Entity\Site();
        $site->setCode('MY-CODE');
        $site->setName("My Code Site Tester");

        $uniqueFields = array('getcode' => 'site', 'caseId');
        $params       = array('site' => $site, 'caseId' => '12223');
        $duplicate    = new \NS\ImportBundle\Filter\Duplicate($uniqueFields);
        $this->assertTrue($duplicate->__invoke($params), "First set of params is not a duplicate");
        $this->assertFalse($duplicate->hasMessage(), "There is no message");
        $this->assertFalse($duplicate->__invoke($params), "Second set of params is a duplicate");
        $this->assertTrue($duplicate->hasMessage(), "There is a message");
        $duplicateArray = $duplicate->toArray();

        $this->assertCount(1, $duplicateArray);
        $this->assertArrayHasKey(0, $duplicateArray);
        $this->assertEquals('my-code_12223', $duplicateArray[0], "The duplicate array has the proper duplicate key");
    }
}
