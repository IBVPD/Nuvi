<?php

namespace NS\SentinelBundle\Repository;

use NS\SentinelBundle\Repository\Common as CommonRepository;

/**
 * Site
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SiteRepository extends CommonRepository
{
    public function getChain($codes = null)
    {
        $queryBuilder = $this->getChainQueryBuilder();

        if(is_array($codes))
            $queryBuilder->andWhere($queryBuilder->expr()->in('s.code', '?1'))->setParameter(1, $codes);
        else if($codes != null)
            $queryBuilder->andWhere('s.code = :code')->setParameter('code',$codes);

        return $queryBuilder->getQuery()->getResult();
    }

    public function getChainQueryBuilder()
    {
        $queryBuilder = $this->_em
            ->createQueryBuilder()
            ->from($this->getEntityName(), 's', 's.code')
            ->select('s,c,r')
            ->innerJoin('s.country', 'c')
            ->innerJoin('c.region', 'r')
            ->where('s.active = :isActive')
            ->setParameter('isActive', true);

        return $this->secure($queryBuilder);
    }

    public function getChainByCode($codes)
    {
        $queryBuilder = $this->getChainQueryBuilder();

        if(is_array($codes))
            $queryBuilder->andWhere($queryBuilder->expr()->in('s.code', '?1'))->setParameter(1, $codes);
        else if(is_string($codes))
            $queryBuilder->andWhere('s.code = :codes')->setParameter('codes', $codes);
        else
            throw new \InvalidArgumentException(sprintf("Must provide an array of codes or single code. Received: %s",gettype($codes)));

        return $this->secure($queryBuilder)->getQuery()->getResult();
    }

    public function findAll()
    {
        return $this->secure($this->createQueryBuilder('s')->where('s.active = :isActive')->setParameter('isActive', true)->orderBy('s.name', 'ASC'))->getQuery()->getResult();
    }

    public function getWithCasesForDate($alias)
    {
        return $this->secure($this->_em->createQueryBuilder()
                  ->select($alias.',s,c,r,COUNT('.$alias.') as totalCases')
                  ->from('NS\SentinelBundle\Entity\IBD',$alias)
                  ->innerJoin($alias.'.site', 's','s.code')
                  ->innerJoin('s.country','c')
                  ->innerJoin('c.region','r')
                  ->groupBy($alias.'.site'))
                  ->addOrderBy('r.name','ASC')
                  ->addOrderBy('c.name','ASC')
                  ->addOrderBy('s.name','ASC');
    }
}