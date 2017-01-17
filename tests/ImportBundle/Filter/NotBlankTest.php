<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 28/03/16
 * Time: 2:30 PM
 */

namespace NS\ImportBundle\Tests\Filter;

use NS\ImportBundle\Filter\NotBlank;

class NotBlankTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialHasNoMessage()
    {
        $filter = new NotBlank(['something']);
        $this->assertFalse($filter->hasMessage());
        $filter->__invoke(['another'=>'thing']);
        $this->assertTrue($filter->hasMessage());
        $this->assertEquals('Field \'something\' is blank', $filter->getMessage());
    }

    public function testHaveFieldHasNoMessage()
    {
        $filter = new NotBlank(['something']);
        $this->assertFalse($filter->hasMessage());
        $filter->__invoke(['something'=>'thing']);
        $this->assertFalse($filter->hasMessage());
    }
}
