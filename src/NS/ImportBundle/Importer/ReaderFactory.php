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
        switch (self::getExtension($file)) {
            case 'csv':
                return new CsvReader($file->openFile('r'));
            case 'xls':
                return new ExcelReader($file->openFile('r'));
        }

        throw new \InvalidArgumentException(sprintf('Unable to find reader for file with extension "%s or %s" mime: %s', $file->getExtension(), $file->guessExtension(), $file->getMimeType()));
    }

    /**
     * @param UploadedFile $file
     * @return CsvReader|ExcelReader
     */
    static public function getUploadedFileExtension(UploadedFile $file)
    {
        return $file->getClientOriginalExtension();
    }

    /**
     * @param File $file
     * @return CsvReader|ExcelReader|null|string
     */
    static public function getExtension(File $file)
    {
        if ($file instanceof UploadedFile) {
            return self::getUploadedFileExtension($file);
        }

        $extension = $file->getExtension();

        if (!$extension) {
            $extension = $file->guessExtension();
        }

        if (in_array($extension, array('xlsx', 'ods'))) {
            $extension = 'xls';
        }

        return $extension;
    }
}
