<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 28/03/16
 * Time: 2:30 PM
 */

namespace NS\ImportBundle\Tests\Filter;

use NS\ImportBundle\Filter\NotBlank;
use PHPUnit\Framework\TestCase;

class NotBlankTest extends TestCase
{
    public function testInitialHasNoMessage(): void
    {
        $filter = new NotBlank(['something']);
        $this->assertFalse($filter->hasMessage());
        $filter->__invoke(['another'=>'thing']);
        $this->assertTrue($filter->hasMessage());
        $this->assertEquals('Field \'something\' is blank', $filter->getMessage());
    }

    public function testHaveFieldHasNoMessage(): void
    {
        $filter = new NotBlank(['something']);
        $this->assertFalse($filter->hasMessage());
        $filter->__invoke(['something'=>'thing']);
        $this->assertFalse($filter->hasMessage());
    }
}
