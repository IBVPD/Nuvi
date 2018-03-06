<?php

namespace NS\SentinelBundle\Filter\Entity;

/**
 * Description of IBD Filter
 * @author gnat
 */
class Meningitis extends BaseCase
{
    private $result;

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}
