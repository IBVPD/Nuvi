<?php

namespace NS\SentinelBundle\Repository\Pneumonia;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnexpectedResultException;
use DoctrineExtensions\Query\Mysql\Month;
use DoctrineExtensions\Query\Mysql\Year;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\ZeroReport;
use NS\SentinelBundle\Exceptions\NonExistentCaseException;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Repository\Common;
use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of Common
 *
 * @author gnat
 */
class PneumoniaRepository extends Common
{
    /**
     * @param string $alias
     * @param int $ageInMonths
     *
     * @return QueryBuilder
     */
    public function numberAndPercentEnrolledByAdmissionDiagnosis($alias = 'c', $ageInMonths = 59): QueryBuilder
    {
        $config = $this->_em->getConfiguration();
        $config->addCustomDatetimeFunction('MONTH', Month::class);

        $queryBuilder = $this->createQueryBuilder($alias)
            ->select(sprintf('MONTH(%s.adm_date) as AdmissionMonth,COUNT(%s.adm_dx) as admDxCount,%s.adm_dx', $alias, $alias, $alias))
            ->where(sprintf('(%s.adm_dx IS NOT NULL AND %s.age_months <= :age)', $alias, $alias))
            ->setParameter('age', $ageInMonths)
            ->groupBy($alias . '.adm_dx,AdmissionMonth');

        return $this->secure($queryBuilder);
    }

    /**
     * @return array
     */
    public function getStats(): array
    {
        $results = ['cxr' => 0];
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m.id) theCount')
            ->where('m.cxr_done = :cxr')
            ->setParameter('cxr', TripleChoice::YES);

        try {
            $results['cxr'] = $this->secure($queryBuilder)->getQuery()->getSingleScalarResult();
        } catch (UnexpectedResultException $exception) {

        }

        return $results;
    }

    /**
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function getLatestQuery($alias = 'm'): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->addSelect('sl,rl,nl')
            ->leftJoin(sprintf('%s.siteLab', $alias), 'sl')
            ->leftJoin(sprintf('%s.referenceLab', $alias), 'rl')
            ->leftJoin(sprintf('%s.nationalLab', $alias), 'nl')
            ->orderBy($alias . '.createdAt', 'DESC');

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
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m) as numberOfCases, partial m.{id,adm_date}, c')
            ->innerJoin('m.country', 'c')
            ->groupBy('m.country');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @return array
     */
    public function getByDiagnosis()
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m) as numberOfCases, partial m.{id,disch_dx}')
            ->groupBy('m.disch_dx');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @return array
     */
    public function getBySite()
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m) as numberOfCases, partial m.{id,adm_date}, s ')
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
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('m,s,c,r')
            ->leftJoin('m.site', 's')
            ->innerJoin('m.country', 'c')
            ->innerJoin('c.region', 'r')
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
    public function search($objId)
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->where('m.id LIKE :id')
            ->setParameter('id', "%$objId%");

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

            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        } catch (UnexpectedResultException $e) {
            throw new NonExistentCaseException('This case does not exist!');
        }
    }

    /**
     * @param mixed $objId
     * @param null $lockMode
     * @param null $lockVersion
     * @return Pneumonia
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

            return $this->secure($queryBuilder)->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getSingleResult();
        } catch (UnexpectedResultException $e) {
            throw new NonExistentCaseException('This case does not exist!');
        }
    }

    public function exportQuery($alias = 'p'): QueryBuilder
    {
        return $this->secure(
            $this->createQueryBuilder($alias)
                ->select($alias . ',sl,rl,nl,s,c,r')
                ->leftJoin($alias.'.siteLab','sl')
                ->leftJoin($alias.'.referenceLab','rl')
                ->leftJoin($alias.'.nationalLab','nl')
                ->innerJoin($alias . '.site', 's')
                ->innerJoin($alias . '.country', 'c')
                ->innerJoin($alias . '.region', 'r')
        );
    }

    public function getFilterQueryBuilder($alias = 'm'): QueryBuilder
    {
        return $this->getLatestQuery($alias);
    }

    /**
     * @param null $modifiedSince
     * @return array
     */
    public function findModified($modifiedSince = null)
    {
        $queryBuilder = $this->getLatestQuery('m');

        if ($modifiedSince) {
            $queryBuilder->where('m.updatedAt >= :updatedAt')->setParameter('updatedAt', $modifiedSince);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     *
     * This depends heavily on NSSentinelBundle:IBD->calculateResult() to calculate the case
     * status properly.
     *
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function getAnnualAgeDistribution($alias = 'm'): QueryBuilder
    {
        $this->_em->getConfiguration()->addCustomDatetimeFunction('YEAR', Year::class);

        $queryBuilder = $this->createQueryBuilder($alias)
            ->select(sprintf('YEAR(%s.adm_date) as theYear, COUNT(%s.id) as theCount,%s.ageDistribution', $alias, $alias, $alias))
            ->where(sprintf('(%s.result = :suspectedMening)', $alias))
            ->setParameter('suspectedMening', CaseResult::PROBABLE)
            ->groupBy(sprintf('theYear,%s.ageDistribution', $alias))
            ->orderBy('theYear', 'ASC');

        return $this->secure($queryBuilder);
    }

    /**
     * @param $alias
     * @param array $siteCodes
     *
     * @return QueryBuilder
     */
    private function getCountQueryBuilder($alias, array $siteCodes): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->leftJoin(sprintf("%s.siteLab", $alias), 'sl')
            ->innerJoin($alias . '.site', 's')
            ->groupBy($alias . '.site');

        if (empty($siteCodes)) {
            return $queryBuilder;
        }

        return $queryBuilder->where("($alias.site IN (:sites) )")->setParameter('sites', $siteCodes);
    }

    /**
     * @param $alias
     * @param array $siteCodes
     *
     * @return QueryBuilder
     */
    public function getBloodCollectedCountBySites($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.blood_collected) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.blood_collected = :bloodCollected', $alias))
            ->setParameter('bloodCollected', TripleChoice::YES);
    }

    /**
     * @param $alias
     * @param array $siteCodes
     *
     * @return QueryBuilder
     */
    public function getBloodResultCountBySites($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere('(sl.blood_cult_result != :unknown OR sl.blood_gram_result != :unknown OR sl.blood_pcr_result != :unknown )')
            ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    /**
     * @param $alias
     * @param array $siteCodes
     *
     * @return QueryBuilder
     */
    public function getMissingAdmissionDiagnosisCountBySites($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('(%s.adm_dx IS NULL OR %s.adm_dx IN (:noSelection,:outOfRange))', $alias, $alias))
            ->setParameter('noSelection', ArrayChoice::NO_SELECTION)
            ->setParameter('outOfRange', ArrayChoice::OUT_OF_RANGE);
    }

    /**
     * @param $alias
     * @param array $siteCodes
     *
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
     *
     * @return QueryBuilder
     */
    public function getMissingDischargeDiagnosisCountBySites($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('(%s.disch_dx IS NULL OR %s.disch_dx IN (:noSelection,:outOfRange))', $alias, $alias))
            ->setParameter('noSelection', ArrayChoice::NO_SELECTION)
            ->setParameter('outOfRange', ArrayChoice::OUT_OF_RANGE);
    }

    public function getConsistentReporting($alias, array $siteCodes): QueryBuilder
    {
        $config = $this->_em->getConfiguration();
        $config->addCustomDatetimeFunction('MONTH', Month::class);

        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,MONTH(%s.adm_date) as theMonth,COUNT(%s.id) as caseCount,s.code', $alias, $alias, $alias))
            ->addGroupBy('theMonth')
            ;
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
            ->setParameter('classType', 'Pneumonia' )
            ->setParameter('sites', $siteCodes);
    }

    public function getNumberOfSpecimenCollectedCount($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.blood_collected = :tripleYes', $alias))
            ->setParameter('tripleYes', TripleChoice::YES);
    }

    /**
     * @param array $params
     * @return bool
     * @throws NonUniqueResultException
     */
    public function exists(array $params)
    {
        $qb = $this->createQueryBuilder('i')->select('i.id');
        foreach ($params as $field => $value) {
            $qb->andWhere(sprintf("%s.%s = :%s", 'i', $field, $field))
                ->setParameter($field, $value);
        }

        try {
            $qb->getQuery()->getSingleResult(Query::HYDRATE_SCALAR);

            return true;
        } catch (NoResultException $exception) {
            return false;
        }
    }

    private function getByCountryCountQueryBuilder($alias, array $countryCodes)
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->innerJoin($alias.'.country', 'c')
            ->groupBy($alias.'.country');

        if (empty($countryCodes)) {
            return $queryBuilder;
        }

        return $queryBuilder->where("($alias.country IN (:countries) )")->setParameter('countries',array_unique($countryCodes));
    }

    public function getLinkedCount($alias, array $countryCodes)
    {
        return $this->getByCountryCountQueryBuilder('cf', $countryCodes)
            ->select(sprintf('COUNT(IDENTITY(%s)) as caseCount,c.code', $alias))
            ->innerJoin('cf.referenceLab', 'i')
            ->innerJoin('cf.site', 's');
    }

    public function getFailedLinkedCount($alias, array $countryCodes)
    {
        return $this->getByCountryCountQueryBuilder('cf', $countryCodes)
            ->select(sprintf('COUNT(IDENTITY(%s)) as caseCount,c.code', $alias))
            ->innerJoin('cf.referenceLab', $alias)
            ->leftJoin('cf.site', 's')
            ->andWhere('s.code IS NULL');
    }

    public function getNoLabCount($alias, array $countryCodes)
    {
        return $this->getByCountryCountQueryBuilder('cf', $countryCodes)
            ->select(sprintf('COUNT(IDENTITY(%s)) as caseCount,c.code', $alias))
            ->leftJoin('cf.referenceLab', $alias)
            ->andWhere(sprintf('IDENTITY(%s) IS NULL',$alias));
    }
}
