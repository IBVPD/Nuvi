<?php

namespace NS\ImportBundle\Tests\Converter;

use NS\ImportBundle\Converter\DateRangeConverter;

class DateRangeConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetKey()
    {
        $converter = new DateRangeConverter();
        $retOne = $converter->getKey('key');
        $this->assertEquals('key', $retOne);

        $retTwo = $converter->getKey('child', 'parent');
        $this->assertEquals('parent.child', $retTwo);

        $retThree = $converter->getKey('subChild', 'parent.child');
        $this->assertEquals('parent.child.subChild', $retThree);
    }

    public function testNoFutureDate()
    {
        $today = new \DateTime();
        $data = [
            'field1' => null,
            'field2' => $today,
            'field3' => new \DateTime('yesterday'),
            'field4' => 'a string',
            'field5' => true,
        ];
        $converter = new DateRangeConverter($today);
        $retData = $converter->__invoke($data);
        $this->assertEquals($data, $retData);
    }

    public function testFutureDate()
    {
        $today = new \DateTime();
        $data = [
            'field1' => null,
            'field2' => new \DateTime('tomorrow'),
            'field3' => new \DateTime('yesterday'),
            'field4' => 'a string',
            'field5' => true,
        ];
        $converter = new DateRangeConverter($today);
        $retData = $converter->__invoke($data);
        $this->assertNotEquals($data, $retData);
        $this->assertNull($retData['field2']);
        $this->assertArrayHasKey('warning', $retData);
        $this->assertTrue($retData['warning']);
        $this->assertTrue($converter->hasMessage());
        $this->assertEquals('[field2] has a date (' . $data['field2']->format('Y-m-d') . ') greater than ('.$today->format('Y-m-d').'). ', $converter->getMessage());
    }

    public function testRecursionNoFutureDate()
    {
        $today = new \DateTime();
        $yester = new \DateTime('yesterday');

        $data = [
            'field1' => null,
            'field2' => $today,
            'field3' => $yester,
            'field4' => 'a string',
            'field5' => true,
        ];
        $sub1 = $data;
        $sub2 = $data;
        $sub1['sub2'] = $sub2;
        $data['sub'] = $sub1;

        $converter = new DateRangeConverter($today);
        $retData = $converter->__invoke($data);
        $this->assertEquals($data, $retData);
    }

    public function testRecursionFutureDate()
    {
        $today = new \DateTime();
        $yester = new \DateTime('yesterday');
        $tomorrow = new \DateTime('2999-01-02');

        $data = [
            'field1' => null,
            'field2' => $today,
            'field3' => $yester,
            'field4' => 'a string',
            'field5' => true,
        ];
        $sub1 = $data;
        $sub2 = $data;
        $sub2['tom'] = $tomorrow;

        $sub1['sub2'] = $sub2;
        $data['sub'] = $sub1;

        $converter = new DateRangeConverter($today);
        $retData = $converter->__invoke($data);
        $this->assertTrue($converter->hasMessage());
        $this->assertEquals('[sub.sub2.tom] has a date (2999-01-02) greater than ('.$today->format('Y-m-d').'). ', $converter->getMessage());
    }

    //=============
    public function testNoPastDate()
    {
        $today = new \DateTime();
        $data = [
            'field1' => null,
            'field2' => $today,
            'field3' => new \DateTime('tomorrow'),
            'field4' => 'a string',
            'field5' => true,
        ];
        $converter = new DateRangeConverter(null, $today);
        $retData = $converter->__invoke($data);
        $this->assertEquals($data, $retData);
    }

    public function testPastDate()
    {
        $today = new \DateTime();
        $data = [
            'field1' => null,
            'field2' => new \DateTime('tomorrow'),
            'field3' => new \DateTime('yesterday'),
            'field4' => 'a string',
            'field5' => true,
        ];
        $converter = new DateRangeConverter(null, $today);
        $retData = $converter->__invoke($data);
        $this->assertNotEquals($data, $retData);
        $this->assertNull($retData['field3']);
        $this->assertArrayHasKey('warning', $retData);
        $this->assertTrue($retData['warning']);
        $this->assertTrue($converter->hasMessage());
        $this->assertEquals('[field3] has a date (' . $data['field3']->format('Y-m-d') . ') less than ('.$today->format('Y-m-d').'). ', $converter->getMessage());
    }

    public function testRecursionNoPastDate()
    {
        $today = new \DateTime();
        $tomorrow = new \DateTime('tomorrow');

        $data = [
            'field1' => null,
            'field2' => $today,
            'field3' => $tomorrow,
            'field4' => 'a string',
            'field5' => true,
        ];
        $sub1 = $data;
        $sub2 = $data;
        $sub1['sub2'] = $sub2;
        $data['sub'] = $sub1;

        $converter = new DateRangeConverter(null, $today);
        $retData = $converter->__invoke($data);
        $this->assertEquals($data, $retData);
    }

    public function testRecursionPastDate()
    {
        $today = new \DateTime();
        $yesterday = new \DateTime('tomorrow');
        $tomorrow = new \DateTime('2001-01-02');

        $data = [
            'field1' => null,
            'field2' => $today,
            'field3' => $yesterday,
            'field4' => 'a string',
            'field5' => true,
        ];
        $sub1 = $data;
        $sub2 = $data;
        $sub2['tom'] = $tomorrow;

        $sub1['sub2'] = $sub2;
        $data['sub'] = $sub1;

        $converter = new DateRangeConverter(null, $today);
        $retData = $converter->__invoke($data);
        $this->assertTrue($converter->hasMessage());
        $this->assertEquals('[sub.sub2.tom] has a date (2001-01-02) less than ('.$today->format('Y-m-d').'). ', $converter->getMessage());
    }

    //=============
    public function testInRangeDate()
    {
        $start = new \DateTime('2015-01-01');
        $end = new \DateTime('2015-12-31');
        $converter = new DateRangeConverter($end, $start);

        $data = [
            'field1' => null,
            'field2' => new \DateTime('2015-09-01'),
            'field3' => new \DateTime('2015-12-27'),
            'field4' => 'a string',
            'field5' => true,
        ];

        $retData = $converter->__invoke($data);
        $this->assertEquals($data, $retData);
    }

    public function testOutOfRangeDate()
    {
        $start = new \DateTime('2015-01-01');
        $end = new \DateTime('2015-12-31');
        $converter = new DateRangeConverter($end, $start);

        $data = [
            'field1' => null,
            'field2' => new \DateTime('2015-11-31'),
            'field3' => new \DateTime('2014-12-31'),
            'field4' => 'a string',
            'field5' => true,
        ];
        $retData = $converter->__invoke($data);
        $this->assertNotEquals($data, $retData);
        $this->assertNull($retData['field3']);
        $this->assertArrayHasKey('warning', $retData);
        $this->assertTrue($retData['warning']);
        $this->assertTrue($converter->hasMessage());
        $this->assertEquals('[field3] has a date (2014-12-31) outside acceptable range (2015-01-01 - 2015-12-31). ', $converter->getMessage());
    }

    public function testRecursionInRangeDate()
    {
        $start = new \DateTime('2015-01-01');
        $end = new \DateTime('2015-12-31');
        $converter = new DateRangeConverter($end, $start);

        $dateOne = new \DateTime('2015-09-01');
        $dateTwo = new \DateTime('2015-12-27');

        $data = [
            'field1' => null,
            'field2' => $dateOne,
            'field3' => $dateTwo,
            'field4' => 'a string',
            'field5' => true,
        ];
        $sub1 = $data;
        $sub2 = $data;
        $sub1['sub2'] = $sub2;
        $data['sub'] = $sub1;

        $retData = $converter->__invoke($data);
        $this->assertEquals($data, $retData);
    }

    public function testRecursionOutOfRangeDate()
    {
        $start = new \DateTime('2015-01-01');
        $end = new \DateTime('2015-12-31');
        $converter = new DateRangeConverter($end, $start);

        $dateOne = new \DateTime('2015-09-01');
        $dateTwo = new \DateTime('2015-12-27');

        $data = [
            'field1' => null,
            'field2' => $dateOne,
            'field3' => $dateTwo,
            'field4' => 'a string',
            'field5' => true,
        ];
        $sub1 = $data;
        $sub2 = $data;
        $sub2['out'] = new \DateTime('2016-01-01');
        $sub1['sub2'] = $sub2;
        $data['sub'] = $sub1;

        $retData = $converter->__invoke($data);
        $this->assertTrue($converter->hasMessage());
        $this->assertEquals('[sub.sub2.out] has a date (2016-01-01) outside acceptable range (2015-01-01 - 2015-12-31). ', $converter->getMessage());
        $this->assertNull($retData['sub']['sub2']['out']);
    }

    public function testWarningOnly()
    {
        $start = new \DateTime('2015-01-01');
        $end = new \DateTime('2015-12-31');
        $converter = new DateRangeConverter($end, $start, true);

        $dateOne = new \DateTime('2015-09-01');
        $dateTwo = new \DateTime('2015-12-27');

        $data = [
            'field1' => null,
            'field2' => $dateOne,
            'field3' => $dateTwo,
            'field4' => 'a string',
            'field5' => true,
        ];
        $sub1 = $data;
        $sub2 = $data;
        $sub2['out'] = new \DateTime('2016-01-01');
        $sub1['sub2'] = $sub2;
        $data['sub'] = $sub1;

        $retData = $converter->__invoke($data);
        $this->assertTrue($converter->hasMessage());
        $this->assertEquals('[sub.sub2.out] has a date (2016-01-01) outside acceptable range (2015-01-01 - 2015-12-31). ', $converter->getMessage());
        $this->assertNotNull($retData['sub']['sub2']['out']);
    }
}
