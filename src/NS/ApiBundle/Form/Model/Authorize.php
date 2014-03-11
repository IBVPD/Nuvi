<?php

namespace NS\ApiBundle\Form\Model;

/**
 * Description of Authorize
 *
 * @author gnat
 */
class Authorize
{
    protected $allowAccess;

    public function getAllowAccess()
    {
        return $this->allowAccess;
    }

    public function setAllowAccess($allowAccess)
    {
        $this->allowAccess = $allowAccess;
    }
}
