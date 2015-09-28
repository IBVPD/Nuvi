<?php

namespace NS\ImportBundle\Tests\Filter;

use NS\ImportBundle\Filter\DateOfBirthFilter;

class DateOfBirthFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getNotSet
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
        $dob = new \DateTime('2015-05-07');
        $admDate = new \DateTime('2015-06-01');

        return array(
            array(
                array('dob' => null), true
            ),
            array(
                array('admDate' => null), true
            ),
            array(
                array('dob' => null, 'admDate' => null), true
            ),
            array(
                array('dob' => null, 'admDate' => $admDate), true
            ),
            array(
                array('dob' => $dob, 'admDate' => null), true
            ),
            array(
                array('dob' => $dob, 'admDate' => null), true
            ),
            array(
                array('dob' => $dob, 'admDate' => $admDate), true
            ),
            array(
                array('dob' => $admDate, 'admDate' => $dob), false
            ),
        );
    }
}
