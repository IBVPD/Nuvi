<?php

namespace NS\ImportBundle\Tests\Exceptions;

use NS\ImportBundle\Exceptions\CaseLinkerNotFoundException;

class CaseLinkerNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \NS\ImportBundle\Exceptions\CaseLinkerNotFoundException
     * @expectedExceptionMessage Unable to locate case linker with service id "ns_import.standard_linker"
     */
    public function testExceptionMessage()
    {
        throw new CaseLinkerNotFoundException("ns_import.standard_linker");
    }
}
