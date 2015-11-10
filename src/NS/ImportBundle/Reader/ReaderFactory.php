<?php

namespace NS\ImportBundle\Reader;

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
        $extension = self::getExtension($file);
        switch ($extension) {
            case 'csv':
                return new CsvReader($file->openFile('r'));
            case 'xlsx':
            case 'xls':
                return new ExcelReader($file->openFile('r'));
        }

        throw new \InvalidArgumentException(sprintf('Unable to find reader for file with extension "%s"', $extension));
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
        $extension = ($file instanceof UploadedFile) ? self::getUploadedFileExtension($file) : $file->getExtension();

        if (!$extension) {
            $extension = $file->guessExtension();
        }

        if (in_array($extension, array('xlsx', 'ods'))) {
            $extension = 'xls';
        }

        return $extension;
    }
}
