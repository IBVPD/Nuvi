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
            $siteChoices  = $nlLab = $rrlLab = array();

            foreach ($metaData->getFieldNames() as $field) {
                $choices[$field] = sprintf('%s (%s)', $field, $metaData->getTypeOfField($field));
            }

            ksort($choices);

            $siteMeta   = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('siteLab'));
            $nlLabMeta  = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('nationalLab'));
            $rrlLabMeta = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('referenceLab'));

            foreach ($siteMeta->getFieldNames() as $siteField) {
                $fieldType           = $siteMeta->getTypeOfField($siteField);
                $field               = sprintf('siteLab.%s', $siteField);
                $siteChoices[$field] = sprintf('%s (%s)',$field,$fieldType);
            }

            ksort($siteChoices);

            foreach ($nlLabMeta->getFieldNames() as $externalField) {
                $fieldType = $nlLabMeta->getTypeOfField($externalField);
                $field     = sprintf('nationalLab.%s', $externalField);
                $nlLab[$field] = sprintf('%s (%s)',$field,$fieldType);
            }

            foreach ($rrlLabMeta->getFieldNames() as $externalField) {
                $fieldType = $rrlLabMeta->getTypeOfField($externalField);
                $field     = sprintf('referenceLab.%s', $externalField);
                $rrlLab[$field] = sprintf('%s (%s)',$field,$fieldType);
            }

            ksort($nlLab);
            ksort($rrlLab);

            $result = array_merge(array('site'=>'site (Site)'),$choices, $siteChoices, $externLabTwo, $externLabOne);
            $this->cache->save($class, $result);

            return $result;
        }

        return $this->cache->fetch($class);
    }
}