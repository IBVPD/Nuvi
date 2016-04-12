<?php

namespace NS\SentinelBundle\Repository;

use \Doctrine\ORM\NoResultException;
use \Doctrine\ORM\Query;
use \NS\SentinelBundle\Exceptions\NonExistentCaseException;
use NS\SentinelBundle\Form\Types\ElisaResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of Common
 *
 * @author gnat
 */
class RotaVirusRepository extends Common
{
    /**
     * @param string $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getLatestQuery($alias = 'm')
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->addSelect('sl,rl,nl')
            ->leftJoin(sprintf('%s.siteLab', $alias), 'sl')
            ->leftJoin(sprintf('%s.nationalLab', $alias), 'nl')
            ->leftJoin(sprintf('%s.referenceLab', $alias), 'rl')
            ->orderBy($alias . '.id', 'DESC');

        return $this->secure($queryBuilder);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getLatest($limit = 10)
    {
        return $this->getLatestQuery()
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function getByCountry()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('COUNT(m) as numberOfCases, partial m.{id,admDate}, c')
            ->from($this->getClassName(), 'm')
            ->innerJoin('m.country', 'c')
            ->groupBy('m.country');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @return array
     */
    public function getByDiagnosis()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('COUNT(m) as numberOfCases, partial m.{id,dischDx}')
            ->from($this->getClassName(), 'm')
            ->groupBy('m.dischDx');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @return array
     */
    public function getBySite()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('COUNT(m) as numberOfCases, partial m.{id,admDate}, s ')
            ->from($this->getClassName(), 'm')
            ->innerJoin('m.site', 's')
            ->groupBy('m.site');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @param $objId
     * @return mixed
     * @throws NonExistentCaseException
     * @throws \Doctrine\ORM\NonUniqueResultException
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
        } catch (NoResultException $e) {
            throw new NonExistentCaseException("This case does not exist!");
        }
    }

    /**
     * @param $objId
     * @return array
     */
    public function search($objId)
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
     * @throws \Doctrine\ORM\NonUniqueResultException
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
        } catch (NoResultException $e) {
            throw new NonExistentCaseException("This case does not exist!");
        }
    }

    /**
     * @param $caseId
     * @param null $objId
     * @return mixed|\NS\SentinelBundle\Entity\RotaVirus
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOrCreate($caseId, $objId = null)
    {
        if ($objId === null && $caseId === null) {
            throw new \InvalidArgumentException("Id or Case must be provided");
        }

        $queryBuilder = $this->createQueryBuilder('m')
            ->select('m,s,c,r')
            ->innerJoin('m.site', 's')
            ->innerJoin('s.country', 'c')
            ->innerJoin('m.region', 'r')
            ->where('m.caseId = :caseId')
            ->setParameter('caseId', $caseId);

        if ($objId) {
            $queryBuilder->orWhere('m.id = :id')->setParameter('id', $objId);
        }

        try {
            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        } catch (NoResultException $exception) {
            $res = new \NS\SentinelBundle\Entity\RotaVirus();
            $res->setCaseId($caseId);

            return $res;
        }
    }

    /**
     * @param mixed $objId
     * @return mixed
     * @throws NonExistentCaseException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function find($objId)
    {
        try {
            $queryBuilder = $this->createQueryBuilder('m')
                ->addSelect('r,c,s')
                ->leftJoin('m.region', 'r')
                ->leftJoin('m.country', 'c')
                ->leftJoin('m.site', 's')
                ->andWhere('m.id = :id')
                ->setParameter('id', $objId);

            $queryBuilder = ($this->hasSecuredQuery()) ? $this->secure($queryBuilder) : $queryBuilder;

            return $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getSingleResult();
        } catch (NoResultException $e) {
            throw new NonExistentCaseException("This case does not exist!");
        }
    }

    /**
     * @param $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function exportQuery($alias)
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
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getFilterQueryBuilder($alias = 'm')
    {
        return $this->secure($this->createQueryBuilder($alias)->orderBy($alias . '.id', 'DESC'));
    }

    /**
     * @param $alias
     * @param array $siteCodes
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getCountQueryBuilder($alias, array $siteCodes)
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->leftJoin("$alias.siteLab", 'sl')
            ->innerJoin("$alias.site", 's')
            ->groupBy("$alias.site");

        $where = $params = array();
        $index = 0;

        if (empty($siteCodes)) {
            return $queryBuilder;
        }

        foreach ($siteCodes as $site) {
            $where[] = "$alias.site = :site$index";
            $params["site$index"] = $site;
            $index++;
        }

        return $queryBuilder->where("(" . implode(" OR ", $where) . ")")->setParameters($params);
    }

    /**
     * @param $alias
     * @param array $siteCodes
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getMissingDischargeOutcomeCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('(%s.dischargeOutcome IS NULL OR %s.dischargeOutcome IN (:noSelection,:outOfRange))', $alias, $alias))
            ->setParameter('noSelection', ArrayChoice::NO_SELECTION)
            ->setParameter('outOfRange', ArrayChoice::OUT_OF_RANGE);
    }

    /**
     * @param $alias
     * @param array $siteCodes
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getStoolCollectionDateErrorCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.stoolCollected = :collectedYes AND %s.stoolCollectionDate IS NULL', $alias, $alias))
            ->setParameter('collectedYes', TripleChoice::YES);
    }

    /**
     * @param $alias
     * @param array $siteCodes
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getMissingDischargeDateCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.dischargeDate IS NULL', $alias));
    }

    public function getConsistentReporting($alias, array $siteCodes)
    {
        $config = $this->_em->getConfiguration();
        $config->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,MONTH(%s.admDate) as theMonth,COUNT(%s.id) as caseCount,s.code', $alias, $alias, $alias))
            ->addGroupBy('theMonth')
            ;
    }

    public function getSpecimenCollectedWithinTwoDays($alias, array $siteCodes)
    {
        $config = $this->_em->getConfiguration();
        $config->addCustomDatetimeFunction('DATEDIFF', 'DoctrineExtensions\Query\Mysql\DateDiff');

        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('DATEDIFF(%s.stoolCollectionDate,%s.admDate) <=2 ', $alias, $alias))
            ;
    }

    public function getLabConfirmedCount($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere('sl.elisaDone = :tripleYes OR sl.elisaResult IS NOT NULL OR sl.elisaResult != \'\'')
            ->setParameter('tripleYes', TripleChoice::YES)
            ;
    }


    private function getByCountryCountQueryBuilder($alias, array $countryCodes)
    {
        $queryBuilder = $this->createQueryBuilder('cf')
            ->innerJoin('cf.country', 'c')
            ->groupBy('cf.country');

        $where = $params = array();
        $index = 0;

        if (empty($countryCodes)) {
            return $queryBuilder;
        }

        foreach (array_unique($countryCodes) as $country) {
            $where[] = "cf.country = :country$index";
            $params['country' . $index] = $country;
            $index++;
        }

        return $queryBuilder->where("(" . implode(" OR ", $where) . ")")->setParameters($params);
    }

    public function getLinkedCount($alias, array $countryCodes)
    {
        return $this->getByCountryCountQueryBuilder($alias, $countryCodes)
            ->select(sprintf('COUNT(%s) as caseCount,c.code', $alias, $alias))
            ->innerJoin('cf.referenceLab', $alias)
            ->innerJoin('cf.site', 's');
    }

    public function getFailedLinkedCount($alias, array $countryCodes)
    {
        return $this->getByCountryCountQueryBuilder($alias, $countryCodes)
            ->select(sprintf('COUNT(%s) as caseCount,c.code', $alias, $alias))
            ->innerJoin('cf.referenceLab', $alias)
            ->leftJoin('cf.site', 's')
            ->andWhere('s.code IS NULL');
    }

    public function getNoLabCount($alias, array $countryCodes)
    {
        return $this->getByCountryCountQueryBuilder($alias, $countryCodes)
            ->select(sprintf('COUNT(%s) as caseCount,c.code', $alias, $alias))
            ->leftJoin('cf.referenceLab', $alias)
            ->andWhere($alias.' IS NULL');
    }

    public function getStoolCollectedCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.stoolCollected = :collectedYes', $alias, $alias))
            ->setParameter('collectedYes', TripleChoice::YES);
    }

    public function getElisaDoneCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.stoolCollected = :collectedYes AND sl.elisaDone = :collectedYes', $alias))
            ->setParameter('collectedYes', TripleChoice::YES);
    }

    public function getElisaPositiveCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.stoolCollected = :collectedYes AND sl.elisaDone = :collectedYes AND sl.elisaResult = :elisaPositive', $alias))
            ->setParameter('collectedYes', TripleChoice::YES)
            ->setParameter('elisaPositive',ElisaResult::POSITIVE);
    }
}
