<?php

namespace NS\ImportBundle\Converter;

use \Doctrine\Common\Cache\CacheProvider;
use \Doctrine\Common\Persistence\Mapping\ClassMetadata;
use \Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\Type;

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
     * @var array
     */
    private $simpleTypes = array(
        Type::TARRAY,
        Type::SIMPLE_ARRAY,
        Type::JSON_ARRAY,
        Type::BIGINT,
        Type::BOOLEAN,
        Type::DECIMAL,
        Type::INTEGER,
        Type::OBJECT,
        Type::SMALLINT,
        Type::STRING,
        Type::TEXT,
        Type::BLOB,
        Type::FLOAT,
        Type::GUID,
    );

    private $results;

    /**
     * ColumnChooser constructor.
     * @param ObjectManager $entityMgr
     * @param CacheProvider $cache
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
        if (!isset($this->results[$class])) {
            $this->results[$class] = $this->getClassData($class);
        }

        return $this->results[$class]['choices'];
    }

    /**
     * @param $class
     * @return mixed
     */
    public function getComplexChoices($class)
    {
        if (!isset($this->results[$class])) {
            $this->results[$class] = $this->getClassData($class);
        }

        return $this->results[$class]['complex'];
    }

    /**
     * @param $class
     * @return array|false|mixed
     */
    public function getClassData($class)
    {
        if (!$this->cache->contains($class)) {
            return $this->build($class);
        }

        return $this->cache->fetch($class);
    }

    /**
     * @param $class
     * @return array
     */
    public function build($class)
    {
        $metaData= $this->entityMgr->getClassMetadata($class);
        $choices = $this->buildChoices($metaData);
        $complex = $this->buildComplex($metaData);
        $result  = array('choices' => $choices,'complex' => $complex);

        $this->cache->save($class, $result);

        return $result;
    }

    /**
     * @param ClassMetadata $metaData
     * @return array
     */
    public function buildChoices(ClassMetadata $metaData)
    {
        $choices  = array('site'=>'site (Site)','country'=>'country (Country)');
        $choices += $this->getMetaChoices($metaData);

        foreach (array('siteLab', 'nationalLab', 'referenceLab') as $metaArg) {
            $associationClass = $metaData->getAssociationTargetClass($metaArg);
            $choices += $this->getMetaChoices($this->entityMgr->getClassMetadata($associationClass), $metaArg);
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

        if ($associationName) {
            foreach ($metadata->getFieldNames() as $fieldName) {
                $fieldType = $metadata->getTypeOfField($fieldName);
                $field = sprintf('%s.%s', $associationName, $fieldName);
                $choices[$field] = sprintf('%s (%s)', $field, $fieldType);
            }
        } else {
            foreach ($metadata->getFieldNames() as $fieldName) {
                $choices[$fieldName] = sprintf('%s (%s)', $fieldName, $metadata->getTypeOfField($fieldName));
            }
        }

        ksort($choices);

        return $choices;
    }

    /**
     * @param ClassMetadata $metaData
     * @return array
     */
    public function buildComplex(ClassMetadata $metaData)
    {
        $choices = array('site'=>true,'country'=>true);
        $choices += $this->getMetaComplexChoices($metaData);

        foreach (array('siteLab', 'nationalLab', 'referenceLab') as $metaArg) {
            $associationClass = $metaData->getAssociationTargetClass($metaArg);
            $choices += $this->getMetaComplexChoices($this->entityMgr->getClassMetadata($associationClass));
        }

        return $choices;
    }

    /**
     * @param ClassMetadata $metadata
     * @return array
     */
    public function getMetaComplexChoices(ClassMetadata $metadata)
    {
        $choices = array();

        foreach ($metadata->getFieldNames() as $fieldName) {
            $fieldType = $metadata->getTypeOfField($fieldName);
            if ($this->isComplex($fieldType)) {
                $choices[$fieldName] = true;
            }
        }

        ksort($choices);

        return $choices;
    }

    /**
     * @param $fieldType
     * @return bool
     */
    public function isComplex($fieldType)
    {
        return !in_array($fieldType, $this->simpleTypes);
    }
}
