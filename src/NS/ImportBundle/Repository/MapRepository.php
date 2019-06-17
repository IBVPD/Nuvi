<?php

namespace NS\ImportBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class MapRepository extends EntityRepository
{
    public function getWithColumnsQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('m')
            ->addSelect('c')
            ->leftJoin('m.columns', 'c')
            ->where('m.active <> false')
            ->orderBy('m.name', 'ASC')
            ->addOrderBy('m.version', 'ASC');
    }

    public function getWithColumns(): array
    {
        return $this->getWithColumnsQuery()->getQuery()->getResult();
    }
}
