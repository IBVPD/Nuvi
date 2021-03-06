<?php

namespace NS\SentinelBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use NS\SentinelBundle\Repository\Common as CommonRepository;

class CountryRepository extends CommonRepository
{
    /**
     * @param null $codes
     * @param bool $includeInactive
     * @return array
     */
    public function getChain($codes = null, $includeInactive = false): array
    {
        $queryBuilder = $this->getChainQueryBuilder($includeInactive);

        if (is_array($codes)) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('c.code', '?1'))->setParameter(1, $codes);
        } elseif ($codes !== null) {
            $queryBuilder->andWhere('c.code = :code')->setParameter('code', $codes);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     *
     * @param bool $includeInactive
     * @return QueryBuilder
     */
    public function getChainQueryBuilder($includeInactive = false): QueryBuilder
    {
        $queryBuilder = $this->_em
            ->createQueryBuilder()
            ->from($this->getEntityName(), 'c', 'c.code')
            ->select('c,r')
            ->innerJoin('c.region', 'r');

        if (!$includeInactive) {
            $queryBuilder->where('c.active = :isActive')->setParameter('isActive', true);
        }

        return $this->secure($queryBuilder);
    }

    /**
     * @param $alias
     * @param $caseClass
     * @return QueryBuilder
     */
    public function getWithCasesForDate($alias, $caseClass): QueryBuilder
    {
        return $this->secure($this->_em->createQueryBuilder()
            ->select("cf, $alias, s, c, r, COUNT(IDENTITY($alias)) as totalCases")
            ->from($caseClass, 'cf')
            ->innerJoin('cf.referenceLab', $alias)
            ->leftJoin('cf.site', 's')
            ->innerJoin('cf.country', 'c')
            ->innerJoin('c.region', 'r')
            ->groupBy('cf.country')
            ->addOrderBy('r.name', 'ASC')
            ->addOrderBy('c.name', 'ASC'));
    }
}
