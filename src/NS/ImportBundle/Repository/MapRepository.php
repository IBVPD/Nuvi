<?php

namespace NS\ImportBundle\Repository;

use \Doctrine\ORM\EntityRepository;
use \Doctrine\ORM\QueryBuilder;

/**
 * Description of MapRepository
 *
 * @author gnat
 */
class MapRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function getWithColumnsQuery()
    {
        return $this->createQueryBuilder('m')->addSelect('c')->leftJoin('m.columns', 'c')->orderBy('m.name', 'ASC')->addOrderBy('m.version', 'ASC');
    }

    /**
     * @return type
     */
    public function getWithColumns()
    {
        return $this->getWithColumnsQuery()->getQuery()->getResult();
    }
}
