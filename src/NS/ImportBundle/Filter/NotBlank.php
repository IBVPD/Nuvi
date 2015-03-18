<?php

namespace NS\ImportBundle\Filter;

use \Ddeboer\DataImport\Filter\FilterInterface;

/**
 * Description of NotBlank
 *
 * @author gnat
 */
class NotBlank implements FilterInterface
{
    public $fields;

    /**
     * @param string $fields The field(s) that will be checked to not be empty
     */
    public function __construct($fields)
    {
        $this->fields = ((is_array($fields)) ? $fields : array($fields));
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
        foreach ($this->fields as $field)
        {
            if (empty($item[$field]))
            {
                return false;
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 1;
    }
}
