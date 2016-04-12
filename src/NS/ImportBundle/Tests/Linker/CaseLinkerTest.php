<?php

namespace NS\ImportBundle\Tests\Linker;

use NS\ImportBundle\Linker\CaseLinker;

class CaseLinkerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $data
     * @param $method
     *
     * @dataProvider getTestData
     */
    public function testBasicFunctions(array $data, $method)
    {
        $linker = new CaseLinker($data, $method);

        $this->assertInstanceOf('NS\ImportBundle\Linker\CaseLinkerInterface', $linker);
        $this->assertEquals($data, $linker->getCriteria());
        $this->assertEquals($method, $linker->getRepositoryMethod());
    }

    public function getTestData()
    {
        return array(
            array(
                array('getcode'=>'site','case_id'),
                'findBySiteAndCaseId',
            ),
        );
    }
}
