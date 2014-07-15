<?php

namespace NS\SentinelBundle\Repository;

use NS\SentinelBundle\Repository\Common as CommonRepository;

/**
 * Site
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Site extends CommonRepository
{
    public function getChain($ids = null)
    {
        $qb = $this->getChainQueryBuilder();

        if(is_array($ids))
            $qb->add('where', $qb->expr()->in('s.id', '?1'))->setParameter(1, $ids);
        else if(is_numeric($ids))
            $qb->andWhere('s.id = :id')->setParameter('id',$ids);

        return $qb->getQuery()->getResult();
    }

    public function getChainQueryBuilder()
    {
        $qb = $this->createQueryBuilder('s')
                ->addSelect('c,r')
                ->innerJoin('s.country', 'c')
                ->innerJoin('c.region', 'r');

        return (method_exists($this, 'secure')) ? $this->secure($qb) : $qb;
    }

    public function getChainByCode($codes)
    {
        $qb = $this->getChainQueryBuilder();

        if(is_array($codes))
            $qb->add('where', $qb->expr()->in('s.code', '?1'))->setParameter(1, $codes);
        else if(is_string($codes))
            $qb->add('where', 's.code = :codes')->setParameter('codes',$codes);
        else
            throw new \InvalidArgumentException(sprintf("Must provide an array of codes or single code. Received: %s",gettype($codes)));

        return $qb->getQuery()->getResult();
    }

    public function findAll()
    {
        $qb = $this->hasSecuredQuery() ? $this->secure($this->createQueryBuilder('s')): $this->createQueryBuilder('s');

        return  $qb->getQuery()->getResult();
    }
}
