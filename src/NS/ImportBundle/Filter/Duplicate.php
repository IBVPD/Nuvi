<?php

namespace NS\ImportBundle\Filter;

use Ddeboer\DataImport\Exception\UnexpectedValueException;
use Ddeboer\DataImport\Filter\FilterInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Duplicate
 *
 * @author gnat
 */
class Duplicate implements FilterInterface
{
    private $items;
    private $fields;
    private $duplicates;

    /**
     * @param array $fields
     */
    public function __construct(array $fields = array())
    {
        $this->items      = new ArrayCollection();
        $this->duplicates = new ArrayCollection();
        $this->fields     = $fields;
    }

    /**
     *
     * @param array $item
     * @return string
     * @throws UnexpectedValueException
     */
    public function getFieldKey(array $item)
    {
        $fieldKey = null;
        foreach ($this->fields as $method => $field)
        {
            if (!isset($item[$field]))
            {
                throw new UnexpectedValueException("'$field' doesn't exist ");
            }

            $fieldKey .= (is_object($item[$field]) && $method && method_exists($item[$field], $method)) ? sprintf('%s-', $item[$field]->$method()) : sprintf('%s-', $item[$field]);
        }

        return substr($fieldKey, 0, -1);
    }

    /**
     * Filter input
     *
     * @param array $item Input
     *
     * @return boolean If false is returned, the workflow will skip the input
     */
    public function filter(array $item)
    {
        $field = $this->getFieldKey($item);

        if (!$this->items->contains($field))
        {
            $this->items->add($field);

            return true;
        }

        $this->duplicates->add($field);

        return false;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->duplicates->toArray();
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return \NS\ImportBundle\Filter\Duplicate
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 2;
    }
}
