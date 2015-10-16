<?php

namespace NS\ImportBundle\Services;

use NS\ImportBundle\Entity\Import;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use \Vich\UploaderBundle\Mapping\PropertyMappingFactory;

class ImportFileCreator
{
    /**
     * @var PropertyMappingFactory
     */
    private $factory;

    /**
     * ImportFileCreator constructor.
     * @param PropertyMappingFactory $factory
     */
    public function __construct(PropertyMappingFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Import $import
     * @param $name
     * @param $property
     * @return File
     */
    public function createNewFile(Import $import, $name, $property)
    {
        $file = new File(tempnam(sys_get_temp_dir(), 'import-output'));
        $mapping = $this->getMapping($import, $property);
        if ($mapping) {
            $mapping->setFileName($import, $name);

            $uploadDir = $this->getUploadDir($import, $mapping);

            return $file->move($uploadDir, $name);
        }

        return $file;
    }

    /**
     * @param Import $import
     * @param $property
     * @return null|PropertyMapping
     */
    public function getMapping(Import $import, $property)
    {
        return $this->factory->fromField($import, $property);
    }

    /**
     * @param Import $import
     * @param PropertyMapping $mapping
     * @return string
     */
    public function getUploadDir(Import $import, PropertyMapping $mapping)
    {
        // determine the file's directory
        $dir = $mapping->getUploadDir($import);

        return $mapping->getUploadDestination() . DIRECTORY_SEPARATOR . $dir;
    }
}
