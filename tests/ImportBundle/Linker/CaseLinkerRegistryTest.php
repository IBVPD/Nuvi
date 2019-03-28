<?php

namespace NS\ImportBundle\Tests\Linker;

use NS\ImportBundle\Linker\CaseLinker;
use NS\ImportBundle\Linker\CaseLinkerRegistry;
use PHPUnit\Framework\TestCase;

class CaseLinkerRegistryTest extends TestCase
{
    public function testLinkersByConstructor(): void
    {
        $linkers  = $this->getLinkers();
        $registry = new CaseLinkerRegistry($linkers);
        $this->assertEquals($linkers['id1'], $registry->getLinker('id1'));
    }

    public function testLinkerBySetter(): void
    {
        $linkers  = $this->getLinkers();
        $registry = new CaseLinkerRegistry();

        foreach ($linkers as $id => $linker) {
            $registry->addLinker($id, $linker);
        }

        $this->assertEquals($linkers['id1'], $registry->getLinker('id1'));
    }

    public function testGetLinkerByNumber(): void
    {
        $linkers  = $this->getLinkers();
        $registry = new CaseLinkerRegistry($linkers);
        $this->assertEquals($linkers[0], $registry->getLinker(0));
    }

    public function getLinkers(): array
    {
        // the third item in this array is 0 because its the first non string based key
        return [
            'id1' => new CaseLinker(['one', 'two'], 'findOneBy'),
            'id2' => new CaseLinker(['three', 'four'], 'findOneBy'),
            new CaseLinker(['two', 'one'], 'findOneBy'),
        ];
    }
}
