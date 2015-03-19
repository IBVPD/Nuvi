<?php

namespace NS\ImportBundle\Filter;

/**
 * Description of AbstractFilterFactory
 *
 * @author gnat
 */
abstract class AbstractFilterFactory implements FilterFactoryInterface
{
    protected $typelist;

    protected $filterClass;

    /**
     *
     * @param string $className
     * @return \Ddeboer\DataImport\Filter\FilterInterface
     * @throws \InvalidArgumentException
     */
    public function createFilter($className)
    {
        if (!$className)
        {
            return null;
        }

        if (!array_key_exists($className, $this->typelist))
        {
            throw new \InvalidArgumentException("$className is not valid type");
        }

        return new $this->filterClass($this->typelist[$className]);
    }

    /**
     *
     * @return array
     */
    public function getTypelist()
    {
        return $this->typelist;
    }

    /**
     *
     * @param array $typelist
     * @return \NS\ImportBundle\Filter\AbstractFilterFactory
     */
    public function setTypelist($typelist)
    {
        $this->typelist = $typelist;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getFilterClass()
    {
        return $this->filterClass;
    }

    /**
     *
     * @param string $filterClass
     * @return \NS\ImportBundle\Filter\AbstractFilterFactory
     */
    public function setFilterClass($filterClass)
    {
        $this->filterClass = $filterClass;
        return $this;
    }
}