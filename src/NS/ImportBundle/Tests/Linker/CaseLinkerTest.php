<?php

namespace NS\ImportBundle\Tests\Linker;


use NS\ImportBundle\Linker\CaseLinker;

class CaseLinkerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $data
     * @param $method
     * @param $id
     *
     * @dataProvider getTestData
     */
    public function testBasicFunctions(array $data, $method, $id)
    {
        $linker = new CaseLinker($data,$method,$id);

        $this->assertInstanceOf('NS\ImportBundle\Linker\CaseLinkerInterface',$linker);
        $this->assertEquals($data,$linker->getCriteria());
        $this->assertEquals($method,$linker->getRepositoryMethod());
        $this->assertEquals($id,$linker->getName());
    }

    public function getTestData()
    {
        return array(
            array(
                array('getcode'=>'site','caseId'),
                'findBySiteAndCaseId',
                'ns_import.some_service',
            ),
        );
    }
}
