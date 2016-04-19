<?php

namespace NS\ImportBundle\Tests\Filter;

use NS\ImportBundle\Filter\DateOfBirthFilter;

class DateOfBirthFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getNotSet
     * @param $data
     * @param $retValue
     */
    public function testNotIsset($data, $retValue)
    {
        $filter = new DateOfBirthFilter();
        if ($retValue) {
            $this->assertTrue($filter->__invoke($data));
            $this->assertFalse($filter->hasMessage());
        } else {
            $this->assertFalse($filter->__invoke($data));
            $this->assertTrue($filter->hasMessage());
            $this->assertEquals('Admission date is before birthdate', $filter->getMessage());
        }
    }

    public function getNotSet()
    {
        $birthdate = new \DateTime('2015-05-07');
        $admDate = new \DateTime('2015-06-01');

        return array(
            array(
                array('birthdate' => null), true
            ),
            array(
                array('adm_date' => null), true
            ),
            array(
                array('birthdate' => null, 'adm_date' => null), true
            ),
            array(
                array('birthdate' => null, 'adm_date' => $admDate), true
            ),
            array(
                array('birthdate' => $birthdate, 'adm_date' => null), true
            ),
            array(
                array('birthdate' => $birthdate, 'adm_date' => null), true
            ),
            array(
                array('birthdate' => $birthdate, 'adm_date' => $admDate), true
            ),
            array(
                array('birthdate' => $admDate, 'adm_date' => $birthdate), false
            ),
        );
    }
}
