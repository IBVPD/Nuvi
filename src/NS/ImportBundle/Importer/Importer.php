<?php

namespace NS\ImportBundle\Importer;

use NS\ImportBundle\Interfaces\Importer;
use NS\ImportBundle\Interfaces\Map;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Description of Importer
 *
 * @author gnat
 */
class Importer implements Importer
{
    private $delimiter;
    private $enclosure;
    private $escape;

    public function process(File $file, Map $map)
    {
        try
        {
            $file = $file->openFile('rb');
        } 
        catch (\RuntimeException $e)
        {
            throw new FileNotFoundException(sprintf('Error opening file "%s".', $file->getFilename()), 0, $e);
        }

        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY );
        $file->setCsvControl($this->delimiter, $this->enclosure, $this->escape);

        $results = array();
        foreach($file as $sourceRow)
        {
            $r = array();
            foreach($sourceRow as $key => $col)
            {
                $r[$key] = $map->getValueForColumn($key,$col);
            }

            if(!empty($r))
                $results[] = $r;
        }

        return $results;
    }

}