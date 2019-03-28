<?php

namespace NS\ImportBundle\Tests\Converter;

use DateTime;
use NS\ImportBundle\Converter\DateOfBirthConverter;
use PHPUnit\Framework\TestCase;

class DateOfBirthConverterTest extends TestCase
{
    /**
     * @dataProvider getData
     * @param $data
     * @param $hasWarning
     */
    public function testNotIsset($data, $hasWarning): void
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

    public function getData(): array
    {
        $birthdate = new DateTime('2015-05-07');
        $admDate = new DateTime('2015-06-01');

        return [
            [['birthdate' => null], false],
            [['adm_date' => null], false],
            [['birthdate' => null, 'adm_date' => null], false],
            [['birthdate' => null, 'adm_date' => $admDate], false],
            [['birthdate' => $birthdate, 'adm_date' => null], false],
            [['birthdate' => $birthdate, 'adm_date' => null], false],
            [['birthdate' => $birthdate, 'adm_date' => $admDate], false],
            [['birthdate' => $admDate, 'adm_date' => $birthdate], true],
        ];
    }
}
