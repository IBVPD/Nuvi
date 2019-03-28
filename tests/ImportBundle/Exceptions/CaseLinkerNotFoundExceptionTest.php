<?php

namespace NS\ImportBundle\Tests\Exceptions;

use NS\ImportBundle\Exceptions\CaseLinkerNotFoundException;
use PHPUnit\Framework\TestCase;

class CaseLinkerNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage(CaseLinkerNotFoundException::class);
        $this->expectExceptionMessage('Unable to locate case linker with service id "ns_import.standard_linker"');
        throw new CaseLinkerNotFoundException('ns_import.standard_linker');
    }
}
