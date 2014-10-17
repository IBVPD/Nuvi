<?php

namespace NS\ImportBundle\Filter;

use \Ddeboer\DataImport\Exception\UnexpectedValueException;
use \Ddeboer\DataImport\Filter\FilterInterface;

/**
 * Description of Unique
 *
 * @author gnat
 */
class Unique implements FilterInterface
{
    private $fields;
    private $classRepository;

    public function __construct($classRepository, array $fields)
    {
        $this->fields          = $fields;
        $this->classRepository = $classRepository;
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
        $params = array();
        foreach($this->fields as $newKey)
        {
            if(!isset($item[$newKey]))
                throw new UnexpectedValueException("NEWKEY $newKey doesn't exist in item");
            else
                $params[$newKey] = $item[$newKey];
        }

        if($this->classRepository->exists($params))
        {
            $exception = $this->getException($params);

            throw new UnexpectedValueException("No id provided however a record already exists",null,$exception);
        }

        return true;
    }

    public function getPriority()
    {
        return 10;
    }

    private function getException(array $params)
    {
        $exceptionMessage = "";
        foreach($params as $key => $value)
        {
            if(is_object($value))
            {
                $str = $value->__toString();
                if(method_exists($value, 'getCode'))
                    $str .= " code: ".$value->getCode();
            }
            else
                $str = $value;

            $exceptionMessage .= sprintf(" %s: %s,", $key, $str);
        }

        return new UnexpectedValueException($exceptionMessage);
    }
}
