<?php

namespace NS\SentinelBundle\Repository;

use Doctrine\ORM\Query;
use NS\SentinelBundle\Repository\Common as CommonRepository;

class RegionRepository extends CommonRepository
{
    /**
     * @return Query
     */
    public function getAllForTree()
    {
        return $this->createQueryBuilder('r')
            ->addSelect('c,s')
            ->leftJoin('r.countries', 'c')
            ->leftJoin('c.sites', 's')
            ->getQuery();
    }

    public function getByCountryIds(array $countryIds)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.countries', 'c')
            ->where('c.code IN (:ids)')
            ->setParameter('ids', $countryIds)
            ->getQuery()
            ->getResult();
    }

    public function getBySiteIds(array $siteIds)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.countries', 'c')
            ->innerJoin('c.sites','s')
            ->where('s.code IN (:ids)')
            ->setParameter('ids', $siteIds)
            ->getQuery()
            ->getResult();
    }
}
