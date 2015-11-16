<?php

namespace NS\ImportBundle\Linker;

use NS\ImportBundle\Exceptions\CaseLinkerNotFoundException;

class CaseLinkerRegistry
{
    /**
     * @var array
     */
    private $caseLinkers;

    /**
     * CaseLinkerRegistry constructor.
     * @param $caseLinkers
     */
    public function __construct($caseLinkers = array())
    {
        foreach($caseLinkers as $id => $linker) {
            $this->addLinker($id,$linker);
        }
    }

    /**
     * @param $id
     * @param CaseLinkerInterface $linker
     */
    public function addLinker($id, CaseLinkerInterface $linker)
    {
        $this->caseLinkers[$id] = $linker;
    }

    /**
     * @param $linkerName
     * @return CaseLinkerInterface
     * @throws CaseLinkerNotFoundException
     */
    public function getLinker($linkerName)
    {
        if(isset($this->caseLinkers[$linkerName])) {
            return $this->caseLinkers[$linkerName];
        }

        throw new CaseLinkerNotFoundException($linkerName);
    }

}
