<?php

namespace NS\SentinelBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnexpectedResultException;
use DoctrineExtensions\Query\Mysql\DateDiff;
use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Entity\ZeroReport;
use NS\SentinelBundle\Exceptions\NonExistentCaseException;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\UtilBundle\Form\Types\ArrayChoice;

class RotaVirusRepository extends Common
{
    /**
     * @param string $alias
     * @return QueryBuilder
     */
    public function getLatestQuery($alias = 'm'): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->addSelect('sl,rl,nl')
            ->leftJoin(sprintf('%s.siteLab', $alias), 'sl')
            ->leftJoin(sprintf('%s.nationalLab', $alias), 'nl')
            ->leftJoin(sprintf('%s.referenceLab', $alias), 'rl')
            ->orderBy($alias . '.createdAt', 'DESC');

        return $this->secure($queryBuilder);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getLatest($limit = 10): array
    {
        return $this->getLatestQuery()
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getByCountry(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('COUNT(m) as numberOfCases, partial m.{id,adm_date}, c')
            ->from($this->getClassName(), 'm')
            ->innerJoin('m.country', 'c')
            ->groupBy('m.country');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getBySite(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('COUNT(m) as numberOfCases, partial m.{id,adm_date}, s ')
            ->from($this->getClassName(), 'm')
            ->innerJoin('m.site', 's')
            ->groupBy('m.site');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @param $objId
     * @return mixed
     * @throws NonExistentCaseException
     */
    public function get($objId)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('m,s,c,r')
            ->from($this->getClassName(), 'm')
            ->innerJoin('m.site', 's')
            ->innerJoin('s.country', 'c')
            ->innerJoin('m.region', 'r')
            ->where('m.id = :id')->setParameter('id', $objId);

        try {
            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        } catch (UnexpectedResultException $e) {
            throw new NonExistentCaseException('This case does not exist!');
        }
    }

    /**
     * @param $objId
     * @return array
     */
    public function search($objId): array
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('m')
            ->from($this->getClassName(), 'm')
            ->where('m.id LIKE :id')->setParameter('id', "%$objId%");

        return $this->secure($queryBuilder)->getQuery()->getResult();
    }

    /**
     * @param $objId
     * @return mixed
     * @throws NonExistentCaseException
     */
    public function checkExistence($objId)
    {
        try {
            $queryBuilder = $this->createQueryBuilder('m')
                ->where('m.id = :id')
                ->setParameter('id', $objId);

            if ($this->hasSecuredQuery()) {
                return $this->secure($queryBuilder)
                    ->getQuery()
                    ->getSingleResult();
            }

            return $queryBuilder->getQuery()->getSingleResult();
        } catch (UnexpectedResultException $e) {
            throw new NonExistentCaseException('This case does not exist!');
        }
    }

    /**
     * @param mixed $objId
     * @param null  $lockMode
     * @param null  $lockVersion
     *
     * @return RotaVirus|null
     */
    public function find($objId, $lockMode = NULL, $lockVersion = NULL)
    {
        try {
            $queryBuilder = $this->createQueryBuilder('m')
                ->addSelect('r,c,s')
                ->leftJoin('m.region', 'r')
                ->leftJoin('m.country', 'c')
                ->leftJoin('m.site', 's')
                ->andWhere('m.id = :id')
                ->setParameter('id', $objId);

            $queryBuilder = $this->hasSecuredQuery() ? $this->secure($queryBuilder) : $queryBuilder;

            return $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getSingleResult();
        } catch (UnexpectedResultException $e) {
            throw new NonExistentCaseException('This case does not exist!');
        }
    }

    /**
     * @param $alias
     * @return QueryBuilder
     */
    public function exportQuery($alias): QueryBuilder
    {
        return $this->secure(
            $this->createQueryBuilder($alias)
                ->select($alias . ',s,c,r')
                ->innerJoin($alias . '.site', 's')
                ->innerJoin($alias . '.country', 'c')
                ->innerJoin($alias . '.region', 'r')
        );
    }

    /**
     * @param string $alias
     * @return QueryBuilder
     */
    public function getFilterQueryBuilder($alias = 'm'): QueryBuilder
    {
        return $this->secure($this->createQueryBuilder($alias)->orderBy($alias . '.id', 'DESC'));
    }

    /**
     * @param $alias
     * @param array $siteCodes
     * @return QueryBuilder
     */
    private function getCountQueryBuilder($alias, array $siteCodes): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->leftJoin("$alias.siteLab", 'sl')
            ->innerJoin("$alias.site", 's')
            ->groupBy("$alias.site");

        $where = $params = [];
        $index = 0;

        if (empty($siteCodes)) {
            return $queryBuilder;
        }

        foreach ($siteCodes as $site) {
            $where[] = "$alias.site = :site$index";
            $params["site$index"] = $site;
            $index++;
        }

        return $queryBuilder->where('(' . implode(' OR ', $where) . ')')->setParameters($params);
    }

    /**
     * @param $alias
     * @param array $siteCodes
     * @return QueryBuilder
     */
    public function getMissingDischargeOutcomeCountBySites($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('(%s.disch_outcome IS NULL OR %s.disch_outcome IN (:noSelection,:outOfRange))', $alias, $alias))
            ->setParameter('noSelection', ArrayChoice::NO_SELECTION)
            ->setParameter('outOfRange', ArrayChoice::OUT_OF_RANGE);
    }

    /**
     * @param $alias
     * @param array $siteCodes
     * @return QueryBuilder
     */
    public function getStoolCollectionDateErrorCountBySites($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.stool_collected = :collectedYes AND %s.stool_collect_date IS NULL', $alias, $alias))
            ->setParameter('collectedYes', TripleChoice::YES);
    }

    /**
     * @param $alias
     * @param array $siteCodes
     * @return QueryBuilder
     */
    public function getMissingDischargeDateCountBySites($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.disch_date IS NULL', $alias));
    }

    public function getConsistentReporting($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,MONTH(%s.adm_date) as theMonth,COUNT(%s.id) as caseCount,s.code', $alias, $alias, $alias))
            ->addGroupBy('theMonth');
    }

    public function getZeroReporting($alias, array $siteCodes): QueryBuilder
    {
        $queryBuilder = $this->_em
            ->getRepository(ZeroReport::class)
            ->createQueryBuilder($alias)
            ->select(sprintf('SUBSTRING(%s.yearMonth,-2) as theMonth, s.code',$alias))
            ->innerJoin($alias . '.site', 's');

        if (empty($siteCodes)) {
            return $queryBuilder;
        }

        return $queryBuilder->where("($alias.type = :type AND $alias.caseType = :classType AND $alias.site IN (:sites))")
            ->setParameter('type', 'zero' )
            ->setParameter('classType', 'NSSentinelBundle:RotaVirus' )
            ->setParameter('sites', $siteCodes);
    }

    public function getSpecimenCollectedWithinTwoDays($alias, array $siteCodes): QueryBuilder
    {
        $config = $this->_em->getConfiguration();
        $config->addCustomDatetimeFunction('DATEDIFF', DateDiff::class);

        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('DATEDIFF(%s.stool_collect_date,%s.adm_date) <=2 ', $alias, $alias))
            ;
    }

    public function getLabConfirmedCount($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere('sl.elisaDone = :tripleYes OR sl.elisaResult IS NOT NULL OR sl.elisaResult != \'\'')
            ->setParameter('tripleYes', TripleChoice::YES)
            ;
    }

    private function getByCountryCountQueryBuilder($alias, array $countryCodes): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->innerJoin('cf.country', 'c')
            ->groupBy('cf.country');

        $where = $params = [];
        $index = 0;

        if (empty($countryCodes)) {
            return $queryBuilder;
        }

        foreach (array_unique($countryCodes) as $country) {
            $where[] = "cf.country = :country$index";
            $params['country' . $index] = $country;
            $index++;
        }

        return $queryBuilder->where('(' . implode(' OR ', $where) . ')')->setParameters($params);
    }

    public function getLinkedCount($alias, array $countryCodes): QueryBuilder
    {
        return $this->getByCountryCountQueryBuilder($alias, $countryCodes)
            ->select(sprintf('IDENTITY(%s),COUNT(IDENTITY(%s)) as caseCount,c.code', $alias, $alias))
            ->innerJoin('cf.referenceLab', $alias)
            ->innerJoin('cf.site', 's');
    }

    public function getFailedLinkedCount($alias, array $countryCodes): QueryBuilder
    {
        return $this->getByCountryCountQueryBuilder($alias, $countryCodes)
            ->select(sprintf('IDENTITY(%s),COUNT(IDENTITY(%s)) as caseCount,c.code', $alias, $alias))
            ->innerJoin('cf.referenceLab', $alias)
            ->leftJoin('cf.site', 's')
            ->andWhere('s.code IS NULL');
    }

    public function getNoLabCount($alias, array $countryCodes): QueryBuilder
    {
        return $this->getByCountryCountQueryBuilder($alias, $countryCodes)
            ->select(sprintf('IDENTITY(%s),COUNT(IDENTITY(%s)) as caseCount,c.code', $alias, $alias))
            ->leftJoin('cf.referenceLab', $alias)
            ->andWhere("IDENTITY($alias) IS NULL");
    }

    public function getStoolCollectedCountBySites($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.stool_collected = :collectedYes', $alias))
            ->setParameter('collectedYes', TripleChoice::YES);
    }

    public function getElisaDoneCountBySites($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.stool_collected = :collectedYes AND sl.elisaDone = :collectedYes', $alias))
            ->setParameter('collectedYes', TripleChoice::YES);
    }

    public function getElisaPositiveCountBySites($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.stool_collected = :collectedYes AND sl.elisaDone = :collectedYes AND sl.elisaResult = :elisaPositive', $alias))
            ->setParameter('collectedYes', TripleChoice::YES)
            ->setParameter('elisaPositive', ElisaResult::POSITIVE);
    }
}
