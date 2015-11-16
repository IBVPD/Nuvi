<?php

namespace NS\SentinelBundle\Converter;

use \Doctrine\Common\Persistence\ObjectManager;
use \NS\ImportBundle\Converter\NamedValueConverterInterface;
use NS\SentinelBundle\Exceptions\NonExistentObjectException;

abstract class AbstractBaseObjectConverter implements NamedValueConverterInterface
{
    /**
     * @var bool
     */
    protected $initialized = false;

    /**
     * @var ObjectManager
     */
    protected $entityMgr;

    /**
     * @var array
     */
    protected $objects = array();

    /**
     * This will likely call findObject and then can run additional tests
     * on the results if anything other than null is returned.
     *
     * @param $input
     * @return object|null
     * @throws NonExistentObjectException
     */
    abstract public function __invoke($input);

    /**
     * @return array
     */
    abstract public function initialize();

    /**
     *
     * @param ObjectManager $entityMgr
     */
    public function __construct(ObjectManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     *
     * @param string $input
     * @return object
     */
    public function findObject($input)
    {
        if (!$this->initialized) {
            $this->objects = $this->initialize();
            $this->initialized = true;
        }

        return isset($this->objects[$input]) ? $this->objects[$input] : null;
    }
}
