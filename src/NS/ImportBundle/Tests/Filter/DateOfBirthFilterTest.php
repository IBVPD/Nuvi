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
    public function testNotIsset($data,$retValue)
    {
        $filter = new DateOfBirthFilter();
        if($retValue) {
            $this->assertTrue($filter->__invoke($data));
        } else {
            $this->assertFalse($filter->__invoke($data));
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
                array('admDate' => null), true
            ),
            array(
                array('birthdate' => null, 'admDate' => null), true
            ),
            array(
                array('birthdate' => null, 'admDate' => $admDate), true
            ),
            array(
                array('birthdate' => $birthdate, 'admDate' => null), true
            ),
            array(
                array('birthdate' => $birthdate, 'admDate' => null), true
            ),
            array(
                array('birthdate' => $birthdate, 'admDate' => $admDate), true
            ),
            array(
                array('birthdate' => $admDate, 'admDate' => $birthdate), false
            ),
        );
    }
}
