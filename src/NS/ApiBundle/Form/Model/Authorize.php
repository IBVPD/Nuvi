<?php

namespace NS\ApiBundle\Form\Model;

/**
 * Description of Authorize
 *
 * @author gnat
 */
class Authorize
{
    /* @var $allowAccess boolean */
    protected $allowAccess = false;

    /**
     *
     * @return boolean
     */
    public function getAllowAccess()
    {
        return $this->allowAccess;
    }

    /**
     *
     * @param boolean $allowAccess
     */
    public function setAllowAccess($allowAccess)
    {
        $this->allowAccess = $allowAccess;
    }
}
