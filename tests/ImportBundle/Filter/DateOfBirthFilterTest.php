<?php

namespace NS\ImportBundle\Tests\Filter;

use DateTime;
use NS\ImportBundle\Filter\DateOfBirthFilter;
use PHPUnit\Framework\TestCase;

class DateOfBirthFilterTest extends TestCase
{
    /**
     * @dataProvider getNotSet
     *
     * @param $data
     * @param $retValue
     */
    public function testNotIsset($data, $retValue): void
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

    public function getNotSet(): array
    {
        $birthdate = new DateTime('2015-05-07');
        $admDate   = new DateTime('2015-06-01');

        return [
            [
                ['birthdate' => null], true,
            ],
            [
                ['adm_date' => null], true,
            ],
            [
                ['birthdate' => null, 'adm_date' => null], true,
            ],
            [
                ['birthdate' => null, 'adm_date' => $admDate], true,
            ],
            [
                ['birthdate' => $birthdate, 'adm_date' => null], true,
            ],
            [
                ['birthdate' => $birthdate, 'adm_date' => null], true,
            ],
            [
                ['birthdate' => $birthdate, 'adm_date' => $admDate], true,
            ],
            [
                ['birthdate' => $admDate, 'adm_date' => $birthdate], false,
            ],
        ];
    }
}
