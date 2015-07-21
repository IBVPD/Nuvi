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
            $choices      = array('site' => 'site (Site)');
            $siteChoices  = $externLabOne = $externLabTwo = array();

            foreach ($metaData->getFieldNames() as $field) {
                $choices[$field] = sprintf('%s (%s)', $field, $metaData->getTypeOfField($field));
            }

            ksort($choices);

            $siteMeta   = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('siteLab'));
            $extLabMeta = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('externalLabs'));

            foreach ($siteMeta->getFieldNames() as $siteField) {
                $fieldType           = $siteMeta->getTypeOfField($siteField);
                $field               = sprintf('siteLab.%s (%s)', $siteField, $fieldType);
                $siteChoices[$field] = $field;
            }

            ksort($siteChoices);

            foreach ($extLabMeta->getFieldNames() as $externalField) {
                $fieldType               = $extLabMeta->getTypeOfField($externalField);
                $fieldOne                = sprintf('referenceLab.%s (%s)', $externalField, $fieldType);
                $fieldTwo                = sprintf('nationalLab.%s (%s)', $externalField, $fieldType);
                $externLabOne[$fieldOne] = $fieldOne;
                $externLabTwo[$fieldTwo] = $fieldTwo;
            }

            ksort($externLabOne);
            ksort($externLabTwo);

            $choices = array_merge($choices, $siteChoices, $externLabTwo, $externLabOne);
            $this->cache->save($class, $choices);

            return $choices;
        }

        return $this->cache->fetch($class);
    }
}