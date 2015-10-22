<?php

namespace NS\ImportBundle\Tests\Importer;


use NS\ImportBundle\Importer\ReaderFactory;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ReaderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getFiles
     */
    public function testReader($file, $extension,$reader)
    {
        $this->assertEquals($extension,ReaderFactory::getExtension($file));
        $this->assertInstanceOf($reader,ReaderFactory::getReader($file));
    }

    public function getFiles()
    {
        return array(
            array(new File(realpath(__DIR__.'/../Fixtures/IBD.csv')),'csv','Ddeboer\DataImport\Reader\CsvReader'),
            array(new UploadedFile(realpath(__DIR__.'/../Fixtures/IBD.csv'),'IBD.csv'),'csv','Ddeboer\DataImport\Reader\CsvReader'),
            array(new File(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.xls')),'xls','Ddeboer\DataImport\Reader\ExcelReader'),
            array(new UploadedFile(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.xls'),'EMR.xls'),'xls','Ddeboer\DataImport\Reader\ExcelReader'),
            array(new File(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.xlsx')),'xls','Ddeboer\DataImport\Reader\ExcelReader'),
            array(new UploadedFile(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.xlsx'),'EMR.xlsx'),'xls','Ddeboer\DataImport\Reader\ExcelReader'),
            array(new File(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.ods')),'xls','Ddeboer\DataImport\Reader\ExcelReader'),
            array(new UploadedFile(realpath(__DIR__.'/../Fixtures/EMR-IBD-headers.ods'),'EMR-IBD-headers.ods'),'xls','Ddeboer\DataImport\Reader\ExcelReader'),
        );
    }
}