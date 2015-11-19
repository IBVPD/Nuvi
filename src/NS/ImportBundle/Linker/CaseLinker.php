<?php

namespace NS\ImportBundle\Linker;

class CaseLinker implements CaseLinkerInterface
{
    /**
     * @var array
     */
    private $criteria;

    /**
     * @var string
     */
    private $repositoryMethod;

    /**
     * CaseLinker constructor.
     * @param array $criteria
     * @param string $repositoryMethod
     */
    public function __construct(array $criteria, $repositoryMethod)
    {
        $this->criteria = $criteria;
        $this->repositoryMethod = $repositoryMethod;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @return mixed
     */
    public function getRepositoryMethod()
    {
        return $this->repositoryMethod;
    }
}
