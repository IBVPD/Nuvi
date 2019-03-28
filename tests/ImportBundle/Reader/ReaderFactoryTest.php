<?php

namespace NS\ImportBundle\Tests\Reader;

use NS\ImportBundle\Reader\ReaderFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Ddeboer\DataImport\Reader\ExcelReader;
use Ddeboer\DataImport\Reader\CsvReader;

class ReaderFactoryTest extends TestCase
{

    /**
     * @param $file
     * @param $extension
     * @param $reader
     *
     * @dataProvider getFiles
     */
    public function testReader($file, $extension, $reader): void
    {
        $factory = new ReaderFactory();
        $this->assertEquals($extension, $factory->getExtension($file));
        $this->assertInstanceOf($reader, $factory->getReader($file));
    }

    public function getFiles(): array
    {
        return [
            [new File(realpath(__DIR__.'/../Fixtures/IBD.csv')),'csv', CsvReader::class],
            [new UploadedFile(realpath(__DIR__.'/../Fixtures/IBD.csv'), 'IBD.csv'),'csv', CsvReader::class],
            [new File(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.xls')),'xls', ExcelReader::class],
            [new UploadedFile(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.xls'), 'EMR.xls'),'xls', ExcelReader::class],
            [new File(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.xlsx')),'xls', ExcelReader::class],
            [new UploadedFile(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.xlsx'), 'EMR.xlsx'),'xls', ExcelReader::class],
            [new File(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.ods')),'xls', ExcelReader::class],
            [new UploadedFile(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.ods'), 'EMR-IBD-headers.ods'),'xls', ExcelReader::class],
        ];
    }
}
