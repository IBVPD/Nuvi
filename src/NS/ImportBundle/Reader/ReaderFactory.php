<?php

namespace NS\ImportBundle\Reader;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ReaderFactory
 * @package NS\ImportBundle\Reader
 */
class ReaderFactory
{
    /**
     * @param File $file
     * @return CsvReader|ExcelReader
     */
    public function getReader(File $file)
    {
        $extension = $this->getExtension($file);
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
    public static function getUploadedFileExtension(UploadedFile $file)
    {
        return $file->getClientOriginalExtension();
    }

    /**
     * @param File $file
     * @return CsvReader|ExcelReader|null|string
     */
    public function getExtension(File $file)
    {
        $extension = ($file instanceof UploadedFile) ? $this->getUploadedFileExtension($file) : $file->getExtension();

        if (!$extension) {
            $extension = $file->guessExtension();
        }

        if (in_array($extension, array('xlsx', 'ods'))) {
            $extension = 'xls';
        }

        return $extension;
    }
}
