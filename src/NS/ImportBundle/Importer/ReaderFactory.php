<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 09/09/15
 * Time: 1:02 PM
 */

namespace NS\ImportBundle\Importer;


use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Reader\ExcelReader;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ReaderFactory
 * @package NS\ImportBundle\Importer
 */
class ReaderFactory
{
    /**
     * @param File $file
     * @return CsvReader|ExcelReader
     */
    static public function getReader(File $file)
    {
        if($file instanceof UploadedFile) {
            return self::getReaderForUploadedFile($file);
        }

        switch($file->getExtension()) {
            case 'csv':
                return new CsvReader($file->openFile('r'));
            case 'xls':
            case 'xlsx':
            case 'ods':
                return new ExcelReader($file->openFile('r'));
        }

        switch($file->guessExtension()) {
            case 'csv':
                return new CsvReader($file->openFile('r'));
            case 'xls':
            case 'xlsx':
            case 'ods':
                return new ExcelReader($file->openFile('r'));
        }

        throw new \InvalidArgumentException(sprintf('Unable to find reader for file with extension "%s or %s" mime:',$file->getExtension(),$file->guessExtension(),$file->getMimeType()));
    }

    /**
     * @param UploadedFile $file
     * @return CsvReader|ExcelReader
     */
    static public function getReaderForUploadedFile(UploadedFile $file)
    {
        switch($file->getClientOriginalExtension()) {
            case 'csv':
                return new CsvReader($file->openFile('r'));
            case 'xls':
            case 'xlsx':
            case 'ods':
                return new ExcelReader($file->openFile('r'));
        }

        throw new \InvalidArgumentException(sprintf('Unable to find reader for file with extension "%s or %s" mime:',$file->getClientOriginalExtension(),$file->getClientMimeType(),$file->getClientMimeType()));
    }
}
