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
    private $field;

    /**
     * @param string $field The field that will be checked to not be empty
     */
    public function __construct($field)
    {
        $this->field = $field;
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
        return !empty($item[$this->field]);
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 2;
    }
}
