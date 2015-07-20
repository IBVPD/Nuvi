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
            $metaData    = $this->entityMgr->getClassMetadata($class);
            $choices     = array('site' => 'site');
            $siteChoices = $externLabOne = $externLabTwo = array();

            foreach ($metaData->getFieldNames() as $field) {
                $choices[$field] = sprintf('%s (%s)',$field,$metaData->getTypeOfField($field));
            }

            ksort($choices);

            $siteMeta   = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('siteLab'));
            $extLabMeta = $this->entityMgr->getClassMetadata($metaData->getAssociationTargetClass('externalLabs'));

            foreach ($siteMeta->getFieldNames() as $siteField) {
                $field               = sprintf('siteLab.%s', $siteField);
                $siteChoices[$field] = $field;
            }

            ksort($siteChoices);

            foreach ($extLabMeta->getFieldNames() as $externalField) {
                $fieldOne                = sprintf('referenceLab.%s', $externalField);
                $fieldTwo                = sprintf('nationalLab.%s', $externalField);
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