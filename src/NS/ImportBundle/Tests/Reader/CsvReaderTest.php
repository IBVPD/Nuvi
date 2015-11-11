<?php

namespace NS\ImportBundle\Tests\Reader;

use NS\ImportBundle\Reader\CsvReader;
use Symfony\Component\HttpFoundation\File\File;

class CsvReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $file = new File(__DIR__ . '/../Fixtures/ReaderOffset.xls');
        $reader = new CsvReader($file->openFile());

        $this->assertInstanceOf('NS\ImportBundle\Reader\OffsetableReaderInterface',$reader);
    }

    public function testNoOffset()
    {
        $file = new File(__DIR__ . '/../Fixtures/ReaderOffset.csv');
        $reader = new CsvReader($file->openFile());
        $this->assertEquals(14, $reader->count());
        foreach ($reader as $index => $row) {
            $this->assertArrayHasKey(0, $row);
            $this->assertEquals('Country', $row[0]);
            break;
        }

        $this->assertEquals(0, $index);
    }

    public function testZeroOffset()
    {
        $file = new File(__DIR__ . '/../Fixtures/ReaderOffset.csv');
        $readerTwo = new CsvReader($file->openFile());
        $readerTwo->setHeaderRowNumber(0);
        $readerTwo->setOffset(0); // effectively not an offset
        $this->assertEquals(0, $readerTwo->getOffset());

        $this->assertEquals(13,$readerTwo->count());

        foreach($readerTwo as $index => $row) {

            $this->assertArrayHasKey('Country',$row);
            $this->assertEquals(18,$row['Country']);

            break;
        }

        $this->assertEquals(1,$index);
    }

    /**
     * @param $offset
     * @param $value
     * @param $endIndex
     *
     * @dataProvider getOffsets
     */
    public function testOffset($offset,$value,$endIndex)
    {
        $file = new File(__DIR__ . '/../Fixtures/ReaderOffset.csv');
        $readerTwo = new CsvReader($file->openFile());
        $readerTwo->setHeaderRowNumber(0);
        $readerTwo->setOffset($offset); // 2 makes us start at row 3 (one after the header row)
        $this->assertEquals(13,$readerTwo->count());
        $this->assertEquals($offset,$readerTwo->getOffset());

        foreach($readerTwo as $index => $row) {
            $this->assertArrayHasKey('Auto_ID',$row);
            $this->assertEquals($value,$row['Auto_ID']);

            break;
        }

        $this->assertEquals($endIndex,$index);
    }

    public function getOffsets()
    {
        return array(
            array(2, 'MSD-GG-00875', 2),
            array(5, 'MSD-GG-00872', 5),
        );

    }
}