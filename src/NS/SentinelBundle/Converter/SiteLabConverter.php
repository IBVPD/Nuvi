<?php

namespace NS\SentinelBundle\Converter;

use Ddeboer\DataImport\ValueConverter\ValueConverterInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NoResultException;

/**
 * Description of SiteLabConverter
 *
 * @author gnat
 */
class SiteLabConverter implements ValueConverterInterface
{
    private $entityMgr;
    private $repository;
    private $entityMetadata;
    private $initialized = false;
    private $class;

    /**
     * @param ObjectManager $entityMgr
     */
    public function __construct(ObjectManager $entityMgr, $class)
    {
        $this->entityMgr = $entityMgr;
        $this->class     = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function convert($input)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        if (!$this->hasNeededFields($input)) {
            return;
        }

        try {
            $siteLab = $this->repository->findBySiteAndCaseId($input['site'], $input['caseId']);
        }
        catch (NoResultException $exception) {
            $siteLab = new $this->class;
        }
        catch (\Exception $except) {
            throw $except;
        }

        $this->updateEntity($input['siteLab'], $siteLab);
        $input['siteLab'] = $siteLab;

        return $input;
    }

    /**
     * 
     * @param array $item
     * @param object $entity
     */
    public function updateEntity($item, $entity)
    {
        $fieldNames = array_merge($this->entityMetadata->getFieldNames(), $this->entityMetadata->getAssociationNames());
        foreach ($fieldNames as $fieldName) {
            $value = null;
            if (isset($item[$fieldName])) {
                $value = $item[$fieldName];
            }
            elseif (method_exists($item, 'get' . ucfirst($fieldName))) {
                $value = $item->{'get' . ucfirst($fieldName)};
            }

            if (null === $value) {
                continue;
            }

            if (!($value instanceof \DateTime) || $value != $this->entityMetadata->getFieldValue($entity, $fieldName)) {
                $setter = 'set' . ucfirst($fieldName);
                $this->setValue($entity, $value, $setter);
            }
        }
    }

    /**
     * 
     * @param object $entity
     * @param mixed $value
     * @param string $setter
     */
    public function setValue($entity, $value, $setter)
    {
        if (method_exists($entity, $setter)) {
            $entity->$setter($value);
        }
    }

    /**
     * 
     */
    public function initialize()
    {
        $this->repository     = $this->entityMgr->getRepository($this->class);
        $this->entityMetadata = $this->entityMgr->getClassMetadata($this->class);
        $this->initialized    = true;
    }

    /**
     * 
     * @param array $input
     * @return boolean
     */
    public function hasNeededFields($input)
    {
        if (!is_array($input)) {
            return false;
        }

        if (!isset($input['siteLab'])) {
            return false;
        }

        if (!isset($input['site']) || !isset($input['caseId'])) {
            return false;
        }

        return true;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return sprintf('%s Converter', $this->class);
    }

    /**
     * 
     * @return string
     */
    public function supportsClass($class)
    {
        if ($this->class == 'NS\SentinelBundle\Entity\IBD\SiteLab' && $class == 'NS\SentinelBundle\Entity\IBD') {
            return true;
        }

        if ($this->class == 'NS\SentinelBundle\Entity\Rota\SiteLab' && $class == 'NS\SentinelBundle\Entity\RotaVirus') {
            return true;
        }

        return false;
    }

}
