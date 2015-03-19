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
    public function convert($input)
    {
        return $this->entityMgr->find($this->className, $input);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}