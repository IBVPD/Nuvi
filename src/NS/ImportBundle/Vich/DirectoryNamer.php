<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 05/08/15
 * Time: 3:21 PM
 */

namespace NS\ImportBundle\Vich;

use \Vich\UploaderBundle\Mapping\PropertyMapping;
use \Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class DirectoryNamer implements DirectoryNamerInterface
{
    /**
     * Creates a directory name for the file being uploaded.
     *
     * @param object $object The object the upload is attached to.
     * @param PropertyMapping $mapping The mapping to use to manipulate the given object.
     *
     * @return string The directory name.
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function directoryName($object, PropertyMapping $mapping)
    {
        return sha1($object->getMapName().$object->getCreatedAt()->format('Y-m-d H:i:s').$object->getUser()->__toString());
    }
}