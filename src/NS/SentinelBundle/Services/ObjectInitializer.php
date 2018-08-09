<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 14/03/17
 * Time: 11:24 AM
 */

namespace NS\SentinelBundle\Services;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Pneumonia;
use NS\SentinelBundle\Entity\Meningitis;
use NS\SentinelBundle\Entity\RotaVirus;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ObjectInitializer
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SerializerInterface */
    private $serializer;

    /** @var AnnotationReader */
    private $annotationReader;

    /** @var bool */
    private $initialized = false;

    /** @var AbstractPlatform */
    private $dbPlatform;

    /** @var PropertyAccessor  */
    private $propertyAccessor;

    /**
     * ObjectInitializer constructor.
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param AnnotationReader $annotationReader
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, AnnotationReader $annotationReader)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->annotationReader = $annotationReader;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param $type
     * @return null|string
     */
    public function initializeObject($type)
    {
        switch ($type) {
            case 'pneumonia':
                $obj = new Pneumonia\Pneumonia();
                $obj->setSiteLab(new Pneumonia\SiteLab());
                $obj->setNationalLab(new Pneumonia\NationalLab());
                $obj->setReferenceLab(new Pneumonia\ReferenceLab());

                break;
            case 'meningitis':
                $obj = new Meningitis\Meningitis();
                $obj->setSiteLab(new Meningitis\SiteLab());
                $obj->setNationalLab(new Meningitis\NationalLab());
                $obj->setReferenceLab(new Meningitis\ReferenceLab());

                break;
            case 'ibd':
                $obj = new IBD();
                $obj->setSiteLab(new IBD\SiteLab());
                $obj->setNationalLab(new IBD\NationalLab());
                $obj->setReferenceLab(new IBD\ReferenceLab());

                break;
            case 'rotavirus':
                $obj = new RotaVirus();
                $obj->setSiteLab(new RotaVirus\SiteLab());
                $obj->setNationalLab(new RotaVirus\NationalLab());
                $obj->setReferenceLab(new RotaVirus\ReferenceLab());

                break;
            default:
                return null;
        }

        if (!$this->initialized) {
            $this->initialize();
        }

        $this->processObject($obj);
        $this->processObject($obj->getSiteLab());
        $this->processObject($obj->getNationalLab());
        $this->processObject($obj->getReferenceLab());

        return $this->serializer->serialize([$type => $obj, 'siteLab' => $obj->getSiteLab(), 'nl' => $obj->getNationalLab(), 'rl' => $obj->getReferenceLab()], 'json', SerializationContext::create()->setGroups(['export','expanded']));
    }

    private function initialize()
    {
        $this->dbPlatform = $this->entityManager->getConnection()->getDatabasePlatform();
        $this->initialized = true;
    }

    /**
     * @param $obj
     */
    private function processObject($obj)
    {
        $reflectionClass = new \ReflectionClass($obj);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $annotations = $this->annotationReader->getPropertyAnnotations($property);
            $column = null;
            $group = null;
            foreach ($annotations as $annotation) {
                if ($annotation instanceof Column) {
                    $column = $annotation;
                }

                if ($annotation instanceof Groups) {
                    $group = $annotation;
                }

                if ($group && $column) {
                    break;
                }
            }

            if ($column) {
                if ($group === null || ($group && in_array('export', $group->groups))) {
                    if ($this->isNonScalar($column->type)) {
                        $type = Type::getType($this->dbPlatform->getDoctrineTypeMapping($column->type));
                        $value = $type->convertToPHPValue(null, $this->dbPlatform);
                    } else {
                        $value = $this->getDefaultScalar($column->type);
                    }

                    $this->propertyAccessor->setValue($obj,$property->getName(),$value);
                }
            }
        }
    }

    /**
     * @param $type
     * @return bool|\DateTime|string
     */
    private function getDefaultScalar($type)
    {
        switch ($type) {
            case 'decimal':
                return '1.0+';
            case 'integer':
                return '1+';
            case 'string':
            case 'text':
                return 'a-z';
            case 'boolean':
                return true;
            case 'date':
            case 'time':
            case 'datetime':
                return new \DateTime();
        }
    }

    /**
     * @param $type
     * @return bool
     */
    private function isNonScalar($type)
    {
        return (!in_array($type, ['integer', 'string', 'text', 'date', 'datetime', 'time', 'boolean']));
    }
}
