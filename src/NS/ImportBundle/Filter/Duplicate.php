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

    public function __construct(array $fields = array())
    {
        $this->items  = new ArrayCollection();
        $this->fields = $fields;
    }

    public function getFieldKey(array $item)
    {
        $fieldKey = null;
        foreach($this->fields as $field)
        {
            if(!isset($item[$field]))
                throw new UnexpectedValueException("'$field' doesn't exist ");

            $fieldKey .= $item[$field];
        }

        return $fieldKey;
    }

    public function filter(array $item)
    {
        $field = $this->getFieldKey($item);

        if(!$this->items->contains($field))
        {
            $this->items->add($field);
            return true;
        }

        return false;
    }

    public function getPriority()
    {
        return 1;
    }
}
