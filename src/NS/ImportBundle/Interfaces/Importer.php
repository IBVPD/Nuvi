<?php

namespace NS\ImportBundle\Interfaces;

use Symfony\Component\HttpFoundation\File\File;

/**
 *
 * @author gnat
 */
interface Importer
{
    public function process(File $file, Map $map);
}