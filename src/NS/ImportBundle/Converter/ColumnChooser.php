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
    private $entityMgr;

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
            $metaData    = $this->entityMgr->getClassMetadata($class);
            $siteMeta    = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('siteLab'));
            $nlLabMeta   = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('nationalLab'));
            $rrlLabMeta  = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('referenceLab'));

            $choices      = $this->getMetaChoices($metaData);
            $siteChoices = $this->getMetaChoices($siteMeta,'siteLab');
            $nlLab       = $this->getMetaChoices($nlLabMeta,'nationalLab');
            $rrlLab      = $this->getMetaChoices($rrlLabMeta,'referenceLab');

            $result = array_merge(array('site'=>'site (Site)'), $choices, $siteChoices, $nlLab, $rrlLab);
            $this->cache->save($class, $result);

            return $result;
        }

        return $this->cache->fetch($class);
    }

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