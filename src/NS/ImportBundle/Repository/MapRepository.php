<?php

namespace NS\ImportBundle\Repository;

/**
 * Description of MapRepository
 *
 * @author gnat
 */
class MapRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function getWithColumnsQuery()
    {
        return $this->createQueryBuilder('m')->addSelect('c')->leftJoin('m.columns', 'c')->orderBy('m.name', 'ASC')->addOrderBy('m.version', 'ASC')->addOrderBy('c.order');
    }

    /**
     * @return type
     */
    public function getWithColumns()
    {
        return $this->getWithColumnsQuery()->getQuery()->getResult();
    }
}