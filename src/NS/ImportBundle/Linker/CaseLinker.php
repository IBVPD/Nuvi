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
     * @var string
     */
    private $serviceId;

    /**
     * CaseLinker constructor.
     * @param array $criteria
     * @param string $repositoryMethod
     * @param string $serviceId
     */
    public function __construct(array $criteria, $repositoryMethod, $serviceId)
    {
        $this->criteria = $criteria;
        $this->repositoryMethod = $repositoryMethod;
        $this->serviceId = $serviceId;
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

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->serviceId;
    }
}
