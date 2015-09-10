<?php

namespace NS\ImportBundle\Converter;

use \Doctrine\Common\Cache\CacheProvider;
use \Doctrine\Common\Persistence\Mapping\ClassMetadata;
use \Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of ColumnChooser
 *
 * @author gnat
 */
class ColumnChooser
{
    /**
     * @var ObjectManager
     */
    private $entityMgr;

    /**
     * @var CacheProvider
     */
    private $cache;

    /**
     * @param ObjectManager $entityMgr
     */
    public function __construct(ObjectManager $entityMgr, CacheProvider $cache)
    {
        $this->entityMgr = $entityMgr;
        $this->cache     = $cache;
    }

    /**
     * @param string $class
     * @return array
     */
    public function getChoices($class)
    {
        if (!$this->cache->contains($class)) {
            $result = $this->buildChoices($class);
            $this->cache->save($class, $result);

            return $result;
        }

        return $this->cache->fetch($class);
    }

    /**
     * @param $class
     * @return array
     */
    public function buildChoices($class)
    {
        $choices  = array('site'=>'site (Site)');
        $metaData = $this->entityMgr->getClassMetadata($class);

        $choices += $this->getMetaChoices($metaData);

        foreach(array('siteLab','nationalLab','referenceLab') as $metaArg) {
            $associationClass = $metaData->getAssociationTargetClass($metaArg);
            $choices += $this->getMetaChoices($this->entityMgr->getClassMetadata($associationClass),$metaArg);
        }

        return $choices;
    }

    /**
     * @param ClassMetadata $metadata
     * @param null $associationName
     * @return array
     */
    public function getMetaChoices(ClassMetadata $metadata, $associationName = null)
    {
        $choices = array();

        if($associationName) {
            foreach ($metadata->getFieldNames() as $fieldName) {
                $fieldType = $metadata->getTypeOfField($fieldName);
                $field     = sprintf('%s.%s', $associationName, $fieldName);
                $choices[$field] = sprintf('%s (%s)',$field,$fieldType);
            }
        } else {
            foreach ($metadata->getFieldNames() as $fieldName) {
                $choices[$fieldName] = sprintf('%s (%s)',$fieldName,$metadata->getTypeOfField($fieldName));
            }
        }

        ksort($choices);

        return $choices;
    }
}
