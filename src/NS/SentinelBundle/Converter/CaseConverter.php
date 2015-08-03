<?php

namespace NS\SentinelBundle\Converter;

use \Doctrine\Common\Persistence\ObjectManager;
use \NS\ImportBundle\Converter\NamedValueConverterInterface;

/**
 * Description of CaseConverter
 *
 * @author gnat
 */
class CaseConverter implements NamedValueConverterInterface
{
    private $entityMgr;
    private $className;
    private $name;

    /**
     * 
     * @param ObjectManager $entityMgr
     * @param string $className
     * @param string $name
     */
    public function __construct(ObjectManager $entityMgr, $className, $name)
    {
        $this->entityMgr = $entityMgr;
        $this->className = $className;
        $this->name      = $name;
    }

    /**
     * @param string $input
     * {@inheritdoc}
     */
    public function __invoke($input)
    {
        if (is_string($input)) {
            return $this->entityMgr->find($this->className, $input);
        }
        else if (is_array($input)) {
            return $this->entityMgr->getRepository($this->className)->findOneBy($input);
        }

        throw new \InvalidArgumentException(sprintf("Input of %s is neither string nor array!", gettype($input)));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
