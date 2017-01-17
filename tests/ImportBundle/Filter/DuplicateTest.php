<?php

namespace NS\ImportBundle\Tests\Filter;

use NS\ImportBundle\Filter\Duplicate;
use NS\SentinelBundle\Entity\Site;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Description of DuplicateTest
 *
 * @author gnat
 */
class DuplicateTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFieldKeyIsLowerCase()
    {
        $file = new File(tempnam(sys_get_temp_dir(), 'duplicate_test'));
        $uniqueFields = ['site', 'case_id'];
        $paramsOne    = ['site' => 'sitecode', 'case_id' => '12223'];
        $paramsTwo    = ['site' => 'sIteCode', 'case_id' => '12223'];

        $duplicate    = new Duplicate($uniqueFields, $file);

        $this->assertFalse($duplicate->hasMessage(), "There is no message ".$duplicate->getMessage());

        $this->assertTrue($duplicate->__invoke($paramsOne), "First set of params is not a duplicate");
        $this->assertFalse($duplicate->hasMessage(), "There is no message ".$duplicate->getMessage());

        $this->assertFalse($duplicate->__invoke($paramsTwo), "Second set of params is a duplicate");
        $this->assertTrue($duplicate->hasMessage(), "There is a message");
        $this->assertEquals('Duplicate row detected with key \'SITECODE_12223\'', $duplicate->getMessage());
        unlink($file->getPathname());
    }

    public function testDuplicateTextOnlyItemIsDetected()
    {
        $file = new File(tempnam(sys_get_temp_dir(), 'duplicate_test'));
        $uniqueFields = ['site', 'case_id'];
        $params       = ['site' => 'sitecode', 'case_id' => '12223'];
        $duplicate    = new Duplicate($uniqueFields,$file);

        $this->assertTrue($duplicate->__invoke($params), "First set of params is not a duplicate");
        $this->assertFalse($duplicate->hasMessage(), "There is no message");
        $this->assertFalse($duplicate->__invoke($params), "Second set of params is a duplicate");
        $this->assertTrue($duplicate->hasMessage(), "There is a message");
        unlink($file->getPathname());
    }

    public function testDuplicateObjectItemIsDetected()
    {
        $file = new File(tempnam(sys_get_temp_dir(), 'duplicate_test'));
        $site = new Site();
        $site->setCode('MY-CODE');
        $site->setName("My Code Site Tester");

        $uniqueFields = ['getcode' => 'site', 'case_id'];
        $params       = ['site' => $site, 'case_id' => '12223'];
        $duplicate    = new Duplicate($uniqueFields,$file);

        $this->assertTrue($duplicate->__invoke($params), "First set of params is not a duplicate");
        $this->assertFalse($duplicate->hasMessage(), "There is no message");
        $this->assertFalse($duplicate->__invoke($params), "Second set of params is a duplicate");
        $this->assertTrue($duplicate->hasMessage(), "There is a message");
        $duplicateArray = $duplicate->toArray();

        $this->assertCount(1, $duplicateArray);
        $this->assertArrayHasKey(0, $duplicateArray);
        $this->assertEquals('MY-CODE_12223', $duplicateArray[0], "The duplicate array has the proper duplicate key");
        unlink($file->getPathname());
    }

    public function testDuplicateObjectStoresState()
    {
        $file = new File(tempnam(sys_get_temp_dir(),'duptest'));
        $uniqueFields = ['code','case_id'];

        $params = [
            ['code'=>1,'case_id'=>2,],
            ['code'=>2,'case_id'=>3,],
            ['code'=>2,'case_id'=>2,],
            ['code'=>3,'case_id'=>1,],
        ];
        $duplicateOne = new Duplicate($uniqueFields, $file);
        foreach ($params as $param) {
            $this->assertTrue($duplicateOne->__invoke($param));
        }
        $duplicateOne->finish();

        $this->assertNotNull(file_get_contents($file->getPathname()));

        $parameter = ['code'=>2,'case_id'=>3];
        $duplicateTwo = new Duplicate($uniqueFields,$file);
        $this->assertFalse($duplicateTwo->__invoke($parameter));
        $this->assertTrue($duplicateTwo->hasMessage());

        unlink($file->getPathname());
    }
}
