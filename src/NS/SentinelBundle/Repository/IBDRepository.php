<?php

namespace NS\SentinelBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnexpectedResultException;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\ZeroReport;
use NS\SentinelBundle\Exceptions\NonExistentCaseException;
use NS\SentinelBundle\Form\IBD\Types\BinaxResult;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\HiSerotype;
use NS\SentinelBundle\Form\IBD\Types\PCRResult;
use NS\SentinelBundle\Form\IBD\Types\SpnSerotype;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\UtilBundle\Form\Types\ArrayChoice;

class IBDRepository extends AbstractReportCommonRepository
{
    /**
     * @param string $alias
     * @param int    $ageInMonths
     *
     * @return QueryBuilder
     */
    public function numberAndPercentEnrolledByAdmissionDiagnosis($alias = 'c', $ageInMonths = 59): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->select(sprintf('MONTH(%s.adm_date) as AdmissionMonth,COUNT(%s.adm_dx) as admDxCount,%s.adm_dx', $alias, $alias, $alias))
            ->where(sprintf('(%s.adm_dx IS NOT NULL AND %s.age_months <= :age)', $alias, $alias))
            ->setParameter('age', $ageInMonths)
            ->groupBy($alias . '.adm_dx,AdmissionMonth');

        return $this->secure($queryBuilder);
    }

    public function getStats(): array
    {
        $results      = [];
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m.id) theCount')
            ->where('m.cxr_done = :cxr')
            ->setParameter('cxr', TripleChoice::YES);

        try {
            $results['cxr'] = $this->secure($queryBuilder)->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            $results['cxr'] = 0;
        }

        $queryBuilder = $this->createQueryBuilder('m')
            ->select('m.csf_collected, COUNT(m.csf_collected) theCount')
            ->groupBy('m.csf_collected');

        $res = $this->secure($queryBuilder)->getQuery()->getResult();

        foreach ($res as $row) {
            if ($row['csf_collected']) {
                $results['csfCollected'] = $row['theCount'];
            } else {
                $results['csfNotCollected'] = $row['theCount'];
            }
        }

        return $results;
    }

    public function getLatestQuery(string $alias = 'm'): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->addSelect('sl,rl,nl')
            ->leftJoin(sprintf('%s.siteLab', $alias), 'sl')
            ->leftJoin(sprintf('%s.referenceLab', $alias), 'rl')
            ->leftJoin(sprintf('%s.nationalLab', $alias), 'nl')
            ->orderBy($alias . '.createdAt', 'DESC');

        return $this->secure($queryBuilder);
    }

    public function getLatest(int $limit = 10): array
    {
        return $this->getLatestQuery()
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getByCountry(): array
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m) as numberOfCases, partial m.{id,adm_date}, c')
            ->innerJoin('m.country', 'c')
            ->groupBy('m.country');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getByDiagnosis(): array
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m) as numberOfCases, partial m.{id,disch_dx}')
            ->groupBy('m.disch_dx');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getBySite(): array
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m) as numberOfCases, partial m.{id,adm_date}, s ')
            ->innerJoin('m.site', 's')
            ->groupBy('m.site');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @param $objId
     *
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

    public function search(string $objId): array
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->where('m.id LIKE :id')
            ->setParameter('id', "%$objId%");

        return $this->secure($queryBuilder)->getQuery()->getResult();
    }

    /**
     * @param $objId
     *
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
     * @param null  $lockMode
     * @param null  $lockVersion
     *
     * @return BaseCase|null
     */
    public function find($objId, $lockMode = null, $lockVersion = null): ?BaseCase
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

    public function exportQuery(string $alias): QueryBuilder
    {
        return $this->secure(
            $this->createQueryBuilder($alias)
                ->select($alias . ',sl,rl,nl,s,c,r')
                ->leftJoin($alias . '.siteLab', 'sl')
                ->leftJoin($alias . '.referenceLab', 'rl')
                ->leftJoin($alias . '.nationalLab', 'nl')
                ->innerJoin($alias . '.site', 's')
                ->innerJoin($alias . '.country', 'c')
                ->innerJoin($alias . '.region', 'r')
        );
    }

    public function getFilterQueryBuilder(string $alias = 'm'): QueryBuilder
    {
        return $this->getLatestQuery($alias);
    }

    /**
     * @param null $modifiedSince
     *
     * @return array
     */
    public function findModified($modifiedSince = null): array
    {
        $queryBuilder = $this->getLatestQuery('m');

        if ($modifiedSince) {
            $queryBuilder->where('m.updatedAt >= :updatedAt')->setParameter('updatedAt', $modifiedSince);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * This depends heavily on NSSentinelBundle:IBD->calculateResult() to calculate the case
     * status properly.
     *
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function getAnnualAgeDistribution(string $alias = 'm'): QueryBuilder
    {
//        $this->_em->getConfiguration()->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');

        $queryBuilder = $this->createQueryBuilder($alias)
            ->select(sprintf('YEAR(%s.adm_date) as theYear, COUNT(%s.id) as theCount,%s.ageDistribution', $alias, $alias, $alias))
            ->where(sprintf('(%s.result = :suspectedMening)', $alias))
            ->setParameter('suspectedMening', CaseResult::PROBABLE)
            ->groupBy(sprintf('theYear,%s.ageDistribution', $alias))
            ->orderBy('theYear', 'ASC');

        return $this->secure($queryBuilder);
    }

    public function getCountQueryBuilder(string $alias, array $siteCodes): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->leftJoin(sprintf('%s.siteLab', $alias), 'sl')
            ->innerJoin($alias . '.site', 's')
            ->groupBy($alias . '.site');

        if (empty($siteCodes)) {
            return $queryBuilder;
        }

        return $queryBuilder->where("($alias.site IN (:sites) )")->setParameter('sites', $siteCodes);
    }

    public function getCsfCollectedCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.csf_collected) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.csf_collected = :csfCollected', $alias))
            ->setParameter('csfCollected', TripleChoice::YES);
    }

    public function getBloodCollectedCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.blood_collected) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('%s.blood_collected = :bloodCollected', $alias))
            ->setParameter('bloodCollected', TripleChoice::YES);
    }

    public function getBloodResultCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere('(sl.blood_cult_result != :unknown OR sl.blood_gram_result != :unknown OR sl.blood_pcr_result != :unknown )')
            ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfResultCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere('sl.csf_cult_result != :unknown')
            ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfBinaxResultCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere('sl.csf_binax_result != :unknown')
            ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfBinaxDoneCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere('sl.csf_binax_done = :yes')
            ->setParameter('yes', TripleChoice::YES);
    }

    public function getCsfLatResultCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere('sl.csf_lat_result != :unknown')
            ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfLatDoneCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere('sl.csf_lat_done = :yes')
            ->setParameter('yes', TripleChoice::YES);
    }

    public function getCsfPcrCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere('sl.csf_pcr_result != :unknown')
            ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfSpnCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->leftJoin(sprintf('%s.referenceLab', $alias), 'rl')
            ->leftJoin(sprintf('%s.nationalLab', $alias), 'nl')
            ->andWhere('(rl.spn_serotype != :other OR rl.spn_serotype != :notDone OR nl.spn_serotype != :other OR nl.spn_serotype != :notDone)')
            ->setParameter('other', SpnSerotype::OTHER)
            ->setParameter('notDone', SpnSerotype::_NOT_DONE);
    }

    public function getCsfHiCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->leftJoin(sprintf('%s.referenceLab', $alias), 'rl')
            ->leftJoin(sprintf('%s.nationalLab', $alias), 'nl')
            ->andWhere('(rl.hi_serotype != :other OR rl.hi_serotype != :notDone OR nl.hi_serotype != :other OR nl.hi_serotype != :notDone)')
            ->setParameter('other', HiSerotype::OTHER)
            ->setParameter('notDone', HiSerotype::NOT_DONE);
    }

    public function getPcrPositiveCountBySites(string $alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere('sl.csf_pcr_result = :spn')
            ->setParameter('spn', PCRResult::SPN);
    }

    public function getCountByCulture(string $alias, bool $culture, ?bool $binax = null, ?bool $pcr = null, array $siteCodes = []): QueryBuilder
    {
        $queryBuilder = $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount, s.code, YEAR(%s.adm_date) as theYear', $alias, $alias, $alias));

        if ($culture) {
            $queryBuilder->andWhere('( sl.csf_cult_result = :spn OR sl.csf_cult_result = :hi OR sl.csf_cult_result = :nm )')
                ->setParameter('spn', CultureResult::SPN)
                ->setParameter('hi', CultureResult::HI)
                ->setParameter('nm', CultureResult::NM);
        } else {
            $queryBuilder->andWhere('( sl.csf_cult_result = :negative )')
                ->setParameter('negative', CultureResult::NEGATIVE);
        }

        if ($binax !== null) {
            $queryBuilder->andWhere('( sl.csf_binax_result = :binax ) ');
            if ($binax) {
                $queryBuilder->setParameter('binax', BinaxResult::POSITIVE);
            } else {
                $queryBuilder->setParameter('binax', BinaxResult::NEGATIVE);
            }
        }

        if ($pcr !== null) {
            if ($pcr) {
                $queryBuilder->andWhere('( sl.csf_pcr_result != :pcrNegative AND sl.csf_pcr_result != :pcrContaminant AND sl.csf_pcr_result != :pcrUnknown )')
                    ->setParameter('pcrNegative', CultureResult::NEGATIVE)
                    ->setParameter('pcrUnknown', CultureResult::UNKNOWN)
                    ->setParameter('pcrContaminant', CultureResult::CONTAMINANT);
            } else {
                $queryBuilder->andWhere('( sl.csf_pcr_result = :pcr )')->setParameter('pcr', CultureResult::NEGATIVE);
            }
        }

        return $queryBuilder;
    }

    /**
     * @param       $alias
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
     * @param       $alias
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
     * @param       $alias
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
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,MONTH(%s.adm_date) as theMonth,COUNT(%s.id) as caseCount,s.code', $alias, $alias, $alias))
            ->addGroupBy('theMonth');
    }

    public function getZeroReporting($alias, array $siteCodes): QueryBuilder
    {
        $queryBuilder = $this->_em
            ->getRepository(ZeroReport::class)
            ->createQueryBuilder($alias)
            ->select(sprintf('SUBSTRING(%s.yearMonth,-2) as theMonth, s.code', $alias))
            ->innerJoin($alias . '.site', 's');

        if (empty($siteCodes)) {
            return $queryBuilder;
        }

        return $queryBuilder->where("($alias.type = :type AND $alias.caseType = :classType AND $alias.site IN (:sites))")
            ->setParameter('type', 'zero')
            ->setParameter('classType', 'NSSentinelBundle:IBD')
            ->setParameter('sites', $siteCodes);
    }

    public function getNumberOfSpecimenCollectedCount($alias, array $siteCodes): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('(%s.csf_collected = :tripleYes OR %s.blood_collected = :tripleYes)', $alias, $alias))
            ->setParameter('tripleYes', TripleChoice::YES);
    }

    public function getNumberOfLabConfirmedCount($alias, array $siteCodes): QueryBuilder
    {
        $tier1Req = 'sl.csf_cult_result IN (:csfResult) OR sl.csf_pcr_result IN (:csfResult)';
        $tier2Req = 'sl.csf_cult_result IN (:csfResult) OR sl.csf_pcr_result IN (:csfResult) OR sl.blood_cult_result IN (:csfResult) OR sl.blood_pcr_result IN (:csfResult)';
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->leftJoin($alias . '.nationalLab', 'nl')
            ->leftJoin($alias . '.referenceLab', 'rl')
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('(
            (nl.spn_serotype > 0 OR rl.spn_serotype > 0 OR nl.hi_serotype > 0 OR rl.hi_serotype > 0 OR nl.nm_serogroup > 0 OR rl.nm_serogroup > 0)
            AND (
                    (s.ibdTier = 1 AND ( %s ) ) OR (s.ibdTier = 2 AND ( %s ) )
                )
            )', $tier1Req, $tier2Req))
            ->setParameter('csfResult', [CultureResult::SPN, CultureResult::HI, CultureResult::NM]);
    }

    public function getNumberOfConfirmedCount($alias, array $siteCodes): QueryBuilder
    {
        $tier1Req = 'sl.csf_cult_result IN (:csfResult) OR sl.csf_pcr_result IN (:csfResult)';
        $tier2Req = 'sl.csf_cult_result IN (:csfResult) OR sl.csf_pcr_result IN (:csfResult) OR sl.blood_cult_result IN (:csfResult) OR sl.blood_pcr_result IN (:csfResult)';
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->leftJoin($alias . '.nationalLab', 'nl')
            ->leftJoin($alias . '.referenceLab', 'rl')
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('(  (s.ibdTier = 1 AND ( %s ) ) OR (s.ibdTier = 2 AND ( %s ) ) )', $tier1Req, $tier2Req))
            ->setParameter('csfResult', [CultureResult::SPN, CultureResult::HI, CultureResult::NM]);
    }

    /**
     * @param array $params
     *
     * @return bool
     * @throws NonUniqueResultException
     */
    public function exists(array $params): ?bool
    {
        $qb = $this->createQueryBuilder('i')->select('i.id');
        foreach ($params as $field => $value) {
            $qb->andWhere(sprintf('%s.%s = :%s', 'i', $field, $field))
                ->setParameter($field, $value);
        }

        try {
            $qb->getQuery()->getSingleResult(Query::HYDRATE_SCALAR);

            return true;
        } catch (NoResultException $exception) {
            return false;
        }
    }

    private function getByCountryCountQueryBuilder($alias, array $countryCodes): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->innerJoin($alias . '.country', 'c')
            ->groupBy($alias . '.country');

        if (empty($countryCodes)) {
            return $queryBuilder;
        }

        return $queryBuilder->where("($alias.country IN (:countries) )")->setParameter('countries', array_unique($countryCodes));
    }

    public function getLinkedCount($alias, array $countryCodes): QueryBuilder
    {
        return $this->getByCountryCountQueryBuilder('cf', $countryCodes)
            ->select(sprintf('COUNT(IDENTITY(%s)) as caseCount,c.code', $alias))
            ->innerJoin('cf.referenceLab', 'i')
            ->innerJoin('cf.site', 's');
    }

    public function getFailedLinkedCount($alias, array $countryCodes): QueryBuilder
    {
        return $this->getByCountryCountQueryBuilder('cf', $countryCodes)
            ->select(sprintf('COUNT(IDENTITY(%s)) as caseCount,c.code', $alias))
            ->innerJoin('cf.referenceLab', $alias)
            ->leftJoin('cf.site', 's')
            ->andWhere('s.code IS NULL');
    }

    public function getNoLabCount($alias, array $countryCodes): QueryBuilder
    {
        return $this->getByCountryCountQueryBuilder('cf', $countryCodes)
            ->select(sprintf('COUNT(IDENTITY(%s)) as caseCount,c.code', $alias))
            ->leftJoin('cf.referenceLab', $alias)
            ->andWhere(sprintf('IDENTITY(%s) IS NULL', $alias));
    }

    public function getSuspectedCountBySites(string $alias, array $siteCodes, ?bool $groupByYear): QueryBuilder
    {
        if ($groupByYear === true) {
            return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id, COUNT(%s.id) as caseCount, YEAR(%s.adm_date) as caseYear, s.code', $alias, $alias, $alias))
                ->andWhere(sprintf('(%s.adm_dx = :suspected)', $alias))
                ->setParameter('suspected', Diagnosis::SUSPECTED_MENINGITIS)
                ->addGroupBy('caseYear');
        }

        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('(%s.adm_dx = :suspected)', $alias))
            ->setParameter('suspected', Diagnosis::SUSPECTED_MENINGITIS);
    }

    public function getSuspectedWithCSFCountBySites(string $alias, array $siteCodes, ?bool $groupByYear = null): QueryBuilder
    {
        return $this->getSuspectedCountBySites($alias, $siteCodes, $groupByYear)
            ->andWhere(sprintf('(%s.csf_collected = :done)', $alias))
            ->setParameter('done', TripleChoice::YES);
    }

    public function getProbableCountBySites(string $alias, array $siteCodes, ?bool $groupByYear = null): QueryBuilder
    {
        if ($groupByYear) {
            return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,YEAR(%s.adm_date) AS caseYear, s.code', $alias, $alias, $alias))
                ->andWhere(sprintf('(%s.result = :probable)', $alias))
                ->setParameter('probable', CaseResult::PROBABLE)
                ->addGroupBy('caseYear');
        }

        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount, s.code', $alias, $alias))
            ->andWhere(sprintf('(%s.result = :probable)', $alias))
            ->setParameter('probable', CaseResult::PROBABLE);

    }

    public function getProbableWithBloodCountBySites(string $alias, array $siteCodes, ?bool $groupByYear = null): QueryBuilder
    {
        return $this->getProbableCountBySites($alias, $siteCodes, $groupByYear)
            ->andWhere(sprintf('(%s.blood_collected = :bCollected)', $alias))
            ->setParameter('bCollected', TripleChoice::YES);
    }
}
