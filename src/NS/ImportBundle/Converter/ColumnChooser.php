<?php

namespace NS\ImportBundle\Converter;

use \Doctrine\Common\Cache\CacheProvider;
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
     */
    public function getChoices($class)
    {
        if (!$this->cache->contains($class)) {
            $metaData     = $this->entityMgr->getClassMetadata($class);
            $choices      = array();
            $siteChoices  = $externLabOne = $externLabTwo = array();

            foreach ($metaData->getFieldNames() as $field) {
                $choices[$field] = sprintf('%s (%s)', $field, $metaData->getTypeOfField($field));
            }

            ksort($choices);

            $siteMeta   = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('siteLab'));
            $extLabMeta = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('externalLabs'));

            foreach ($siteMeta->getFieldNames() as $siteField) {
                $fieldType           = $siteMeta->getTypeOfField($siteField);
                $field               = sprintf('siteLab.%s', $siteField);
                $siteChoices[$field] = sprintf('%s (%s)',$field,$fieldType);
            }

            ksort($siteChoices);

            foreach ($extLabMeta->getFieldNames() as $externalField) {
                $fieldType               = $extLabMeta->getTypeOfField($externalField);
                $fieldOne                = sprintf('referenceLab.%s', $externalField);
                $fieldTwo                = sprintf('nationalLab.%s', $externalField);
                $externLabOne[$fieldOne] = sprintf('%s (%s)',$fieldOne,$fieldType);
                $externLabOne[$fieldTwo] = sprintf('%s (%s)',$fieldTwo,$fieldType);
            }

            ksort($externLabOne);
            ksort($externLabTwo);

            $result = array_merge(array('site'=>'site (Site)'),$choices, $siteChoices, $externLabTwo, $externLabOne);
            $this->cache->save($class, $result);

            return $result;
        }

        return $this->cache->fetch($class);
    }
}