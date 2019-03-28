<?php

namespace NS\ImportBundle\Tests\Reader;

use NS\ImportBundle\Reader\ExcelReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use NS\ImportBundle\Reader\OffsetableReaderInterface;

class ExcelReaderTest extends TestCase
{
    public function testImplementsInterface(): void
    {
        $file = new File(__DIR__ . '/../Fixtures/ReaderOffset.xls');
        $reader = new ExcelReader($file->openFile());

        $this->assertInstanceOf(OffsetableReaderInterface::class, $reader);
    }

    public function testNoOffset(): void
    {
        $file = new File(__DIR__ . '/../Fixtures/ReaderOffset.xls');
        $reader = new ExcelReader($file->openFile());
        $this->assertEquals(14, $reader->count());
        foreach ($reader as $index => $row) {
            $this->assertArrayHasKey(0, $row);
            $this->assertEquals('Country', $row[0]);
            break;
        }

        $this->assertEquals(1, $index);
        $readerTwo = new ExcelReader($file->openFile());
        $readerTwo->setHeaderRowNumber(0);
        $readerTwo->setOffset(0); // effectively not an offset
        $this->assertEquals(-1, $readerTwo->getOffset());

        $this->assertEquals(13, $readerTwo->count());

        foreach ($readerTwo as $index => $row) {
            $this->assertArrayHasKey('Country', $row);
            $this->assertEquals(18, $row['Country']);

            break;
        }
        $this->assertEquals(2, $index);
    }

    /**
     * @param $offset
     * @param $value
     * @param $endIndex
     *
     * @dataProvider getOffsets
     */
    public function testOffset($offset, $value, $endIndex): void
    {
        $file = new File(__DIR__ . '/../Fixtures/ReaderOffset.xls');
        $readerTwo = new ExcelReader($file->openFile());
        $readerTwo->setHeaderRowNumber(0);
        $readerTwo->setOffset($offset); // 2 makes us start at row 3 (one after the header row)
        $this->assertEquals($offset-1, $readerTwo->getOffset());
        $this->assertEquals(13, $readerTwo->count());

        foreach ($readerTwo as $index => $row) {
            $this->assertArrayHasKey('Auto_ID', $row);
            $this->assertEquals($value, $row['Auto_ID']);

            break;
        }

        $this->assertEquals($endIndex, $index);
    }

    public function getOffsets(): array
    {
        return [
            [2, 'MSD-GG-00875', 3],
            [5, 'MSD-GG-00872', 6],
        ];
    }
}
