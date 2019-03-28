<?php

namespace NS\ImportBundle\Tests\Linker;

use NS\ImportBundle\Linker\CaseLinker;
use PHPUnit\Framework\TestCase;
use NS\ImportBundle\Linker\CaseLinkerInterface;

class CaseLinkerTest extends TestCase
{
    /**
     * @param array $data
     * @param       $method
     *
     * @dataProvider getTestData
     */
    public function testBasicFunctions(array $data, $method): void
    {
        $linker = new CaseLinker($data, $method);

        $this->assertInstanceOf(CaseLinkerInterface::class, $linker);
        $this->assertEquals($data, $linker->getCriteria());
        $this->assertEquals($method, $linker->getRepositoryMethod());
    }

    public function getTestData(): array
    {
        return [
            [
                ['getcode' => 'site', 'case_id'],
                'findBySiteAndCaseId',
            ],
        ];
    }
}
