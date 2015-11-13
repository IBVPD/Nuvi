<?php

namespace NS\ImportBundle\Tests\Linker;

use NS\ImportBundle\Linker\CaseLinker;
use NS\ImportBundle\Linker\CaseLinkerRegistry;

class CaseLinkerRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testLinkersByConstructor()
    {
        $linkers= $this->getLinkers();
        $registry = new CaseLinkerRegistry($linkers);
        $this->assertEquals($linkers['id1'],$registry->getLinker('id1'));
    }

    public function testLinkerBySetter()
    {
        $linkers= $this->getLinkers();
        $registry = new CaseLinkerRegistry();

        foreach($linkers as $id => $linker) {
            $registry->addLinker($id,$linker);
        }

        $this->assertEquals($linkers['id1'],$registry->getLinker('id1'));
    }

    public function testGetLinkerByNumber()
    {
        $linkers= $this->getLinkers();
        $registry = new CaseLinkerRegistry($linkers);
        $this->assertEquals($linkers[0],$registry->getLinker(0));
    }

    public function getLinkers()
    {
        // the third item in this array is 0 because its the first non string based key
        return array(
            'id1' => new CaseLinker(array('one','two'),'findOneBy'),
            'id2' => new CaseLinker(array('three','four'),'findOneBy'),
            new CaseLinker(array('two','one'),'findOneBy'),
        );
    }
}
