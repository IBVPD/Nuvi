<?php

namespace NS\ImportBundle\Tests\Converter;

use NS\ImportBundle\Converter\DateOfBirthConverter;

class DateOfBirthConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getData
     * @param $data
     * @param $hasWarning
     */
    public function testNotIsset($data, $hasWarning)
    {
        $filter = new DateOfBirthConverter();
        $this->assertFalse($filter->hasMessage());
        $item = $filter->__invoke($data);

        if ($hasWarning) {
            $this->assertTrue($filter->hasMessage());
            $this->assertArrayHasKey('warning', $item);
        } else {
            $this->assertFalse($filter->hasMessage());
            $this->assertArrayNotHasKey('warning', $item);
        }
    }

    public function getData()
    {
        $birthdate = new \DateTime('2015-05-07');
        $admDate = new \DateTime('2015-06-01');

        return array(
            array(array('birthdate' => null), false ),
            array(array('admDate' => null), false),
            array(array('birthdate' => null, 'admDate' => null), false),
            array(array('birthdate' => null, 'admDate' => $admDate), false),
            array(array('birthdate' => $birthdate, 'admDate' => null), false),
            array(array('birthdate' => $birthdate, 'admDate' => null), false),
            array(array('birthdate' => $birthdate, 'admDate' => $admDate), false),
            array(array('birthdate' => $admDate, 'admDate' => $birthdate), true),
        );
    }
}
