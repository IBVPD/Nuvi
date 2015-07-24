<?php

namespace NS\SentinelBundle\Repository;

use \Doctrine\ORM\NoResultException;
use \Doctrine\ORM\Query;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
use \NS\SentinelBundle\Form\Types\BinaxResult;
use \NS\SentinelBundle\Form\Types\CultureResult;
use \NS\SentinelBundle\Form\Types\HiSerotype;
use \NS\SentinelBundle\Form\Types\IBDCaseResult;
use \NS\SentinelBundle\Form\Types\PCRResult;
use \NS\SentinelBundle\Form\Types\SpnSerotype;
use \NS\SentinelBundle\Form\Types\TripleChoice;

/**
 * Description of Common
 *
 * @author gnat
 */
class IBDRepository extends Common
{
    public function numberAndPercentEnrolledByAdmissionDiagnosis($alias = 'c', $ageInMonths = 59)
    {
        $config = $this->_em->getConfiguration();
        $config->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

        $queryBuilder = $this->createQueryBuilder($alias)
            ->select(sprintf('MONTH(%s.admDate) as AdmissionMonth,COUNT(%s.admDx) as admDxCount,%s.admDx', $alias, $alias, $alias))
            ->where(sprintf("(%s.admDx IS NOT NULL AND %s.age <= :age)", $alias, $alias))
            ->setParameter('age', $ageInMonths)
            ->groupBy($alias . '.admDx,AdmissionMonth');

        return $this->secure($queryBuilder);
    }

    public function getStats()
    {
        $results      = array();
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m.id) theCount')
            ->where('m.cxrDone = :cxr')
            ->setParameter('cxr', TripleChoice::YES);

        $results['cxr'] = $this->secure($queryBuilder)->getQuery()->getSingleScalarResult();

        $queryBuilder = $this->createQueryBuilder('m')
            ->select('m.csfCollected, COUNT(m.csfCollected) theCount')
            ->groupBy('m.csfCollected');

        $res = $this->secure($queryBuilder)->getQuery()->getResult();

        foreach ($res as $row)
        {
            if ($row['csfCollected'])
                $results['csfCollected']    = $row['theCount'];
            else
                $results['csfNotCollected'] = $row['theCount'];
        }

        return $results;
    }

    public function getLatestQuery($alias = 'm')
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->addSelect("sl,el")
            ->leftJoin(sprintf("%s.siteLab", $alias), "sl")
            ->leftJoin(sprintf("%s.externalLabs", $alias), "el")
            ->orderBy($alias . '.id', 'DESC');

        return $this->secure($queryBuilder);
    }

    public function getLatest($limit = 10)
    {
        return $this->getLatestQuery()
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
    }

    public function getByCountry()
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m) as numberOfCases, partial m.{id,admDate}, c')
            ->innerJoin('m.country', 'c')
            ->groupBy('m.country');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getByDiagnosis()
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m) as numberOfCases, partial m.{id,dischDx}')
            ->groupBy('m.dischDx');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getBySite()
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('COUNT(m) as numberOfCases, partial m.{id,admDate}, s ')
            ->innerJoin('m.site', 's')
            ->groupBy('m.site');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function get($objId)
    {
        $queryBuilder = $this->createQueryBuilder('m')
                ->select('m,s,c,r')
                ->innerJoin('m.site', 's')
                ->innerJoin('s.country', 'c')
                ->innerJoin('m.region', 'r')
                ->where('m.id = :id')->setParameter('id', $objId);
        try
        {
            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        }
        catch (NoResultException $e)
        {
            throw new NonExistentCase("This case does not exist!");
        }
    }

    public function search($objId)
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->where('m.id LIKE :id')
            ->setParameter('id', "%$objId%");

        return $this->secure($queryBuilder)->getQuery()->getResult();
    }

    public function checkExistence($objId)
    {
        try
        {
            $queryBuilder = $this->createQueryBuilder('m')
                ->where('m.id = :id')
                ->setParameter('id', $objId);

            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        }
        catch (NoResultException $e)
        {
            throw new NonExistentCase("This case does not exist!");
        }
    }

    public function find($objId)
    {
        try
        {
            $queryBuilder = $this->createQueryBuilder('m')
                ->addSelect('r,c,s')
                ->leftJoin('m.region', 'r')
                ->leftJoin('m.country', 'c')
                ->leftJoin('m.site', 's')
                ->andWhere('m.id = :id')
                ->setParameter('id', $objId);

            return $this->secure($queryBuilder)->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getSingleResult();
        }
        catch (NoResultException $e)
        {
            return null; //throw new NonExistentCase("This case does not exist!");
        }
    }

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

    public function getFilterQueryBuilder($alias = 'm')
    {
        return $this->getLatestQuery($alias);
    }

    public function findModified($modifiedSince = null)
    {
        $queryBuilder = $this->getLatestQuery('m');

        if ($modifiedSince)
            $queryBuilder->where('m.updatedAt >= :updatedAt')->setParameter('updatedAt', $modifiedSince);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     *
     * This depends heavily on NSSentinelBundle:IBD->calculateResult() to calculate the case
     * status properly.
     * 
     * @return array
     */
    public function getAnnualAgeDistribution($alias = 'm')
    {
        $this->_em->getConfiguration()->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');

        $queryBuilder = $this->createQueryBuilder($alias)
            ->select(sprintf('YEAR(%s.admDate) as theYear, COUNT(%s.id) as theCount,%s.ageDistribution', $alias, $alias, $alias))
            ->where(sprintf('(%s.result = :suspectedMening)', $alias))
            ->setParameter('suspectedMening', IBDCaseResult::PROBABLE)
            ->groupBy(sprintf('theYear,%s.ageDistribution', $alias))
            ->orderBy('theYear', 'ASC')
        ;

        return $this->secure($queryBuilder);
    }

    private function getCountQueryBuilder($alias, array $siteCodes)
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->leftJoin(sprintf("%s.siteLab", $alias), 'sl')
            ->innerJoin($alias . '.site', 's')
            ->groupBy($alias . '.site');

        $where = $params = array();
        $index = 0;

        if (empty($siteCodes))
            return $queryBuilder;

        foreach ($siteCodes as $site)
        {
            $where[]                 = "$alias.site = :site$index";
            $params['site' . $index] = $site;
            $index++;
        }

        return $queryBuilder->where("(" . implode(" OR ", $where) . ")")->setParameters($params);
    }

    public function getCsfCollectedCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.csfCollected) as caseCount,s.code', $alias, $alias))
                ->andWhere(sprintf('%s.csfCollected = :csfCollected', $alias))
                ->setParameter('csfCollected', TripleChoice::YES);
    }

    public function getBloodCollectedCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.bloodCollected) as caseCount,s.code', $alias, $alias))
                ->andWhere(sprintf('%s.bloodCollected = :bloodCollected', $alias))
                ->setParameter('bloodCollected', TripleChoice::YES);
    }

    public function getBloodResultCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
                ->andWhere('(sl.bloodCultResult != :unknown OR sl.bloodGramResult != :unknown OR sl.bloodPcrResult != :unknown )')
                ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfResultCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
                ->andWhere('sl.csfCultResult != :unknown')
                ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfBinaxResultCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
                ->andWhere('sl.csfBinaxResult != :unknown')
                ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfBinaxDoneCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
                ->andWhere('sl.csfBinaxDone = :yes')
                ->setParameter('yes', TripleChoice::YES);
    }

    public function getCsfLatResultCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
                ->andWhere('sl.csfLatResult != :unknown')
                ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfLatDoneCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
                ->andWhere('sl.csfLatDone = :yes')
                ->setParameter('yes', TripleChoice::YES);
    }

    public function getCsfPcrCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
                ->andWhere('sl.csfPcrResult != :unknown')
                ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfSpnCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
                ->andWhere('(sl.spnSerotype != :other OR sl.spnSerotype != :notDone)')
                ->setParameter('other', SpnSerotype::OTHER)
                ->setParameter('notDone', SpnSerotype::_NOT_DONE);
    }

    public function getCsfHiCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
                ->andWhere('(sl.hiSerotype != :other OR sl.hiSerotype != :notDone)')
                ->setParameter('other', HiSerotype::OTHER)
                ->setParameter('notDone', HiSerotype::NOT_DONE);
    }

    public function getPcrPositiveCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
                ->andWhere('sl.csfPcrResult = :spn')
                ->setParameter('spn', PCRResult::SPN);
    }

    public function getCountByCulture($alias, $culture, $binax = null, $pcr = null, array $siteCodes = array())
    {
        $this->_em->getConfiguration()->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');

        $queryBuilder = $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount, s.code, YEAR(%s.admDate) as theYear', $alias, $alias, $alias))
        ;

        if ($culture)
        {
            $queryBuilder->andWhere('( sl.csfCultResult = :spn OR sl.csfCultResult = :hi OR sl.csfCultResult = :nm )')
                ->setParameter('spn', CultureResult::SPN)
                ->setParameter('hi', CultureResult::HI)
                ->setParameter('nm', CultureResult::NM);
        }
        else
        {
            $queryBuilder->andWhere('( sl.csfCultResult = :negative )')
                ->setParameter('negative', CultureResult::NEGATIVE);
        }

        if (!is_null($binax))
        {
            $queryBuilder->andWhere('( sl.csfBinaxResult = :binax ) ');
            if ($binax)
                $queryBuilder->setParameter('binax', BinaxResult::POSITIVE);
            else
                $queryBuilder->setParameter('binax', BinaxResult::NEGATIVE);
        }

        if (!is_null($pcr))
        {
            if ($pcr)
                $queryBuilder->andWhere('( sl.csfPcrResult != :pcrNegative AND sl.csfPcrResult != :pcrContaminant AND sl.csfPcrResult != :pcrUnknown )')
                    ->setParameter('pcrNegative', CultureResult::NEGATIVE)
                    ->setParameter('pcrUnknown', CultureResult::UNKNOWN)
                    ->setParameter('pcrContaminant', CultureResult::CONTAMINANT);
            else
                $queryBuilder->andWhere('( sl.csfPcrResult = :pcr )')->setParameter('pcr', CultureResult::NEGATIVE);
        }

        return $queryBuilder;
    }

    public function exists(array $params)
    {
        $qb = $this->createQueryBuilder('i')->select('i.id');
        foreach ($params as $field => $value)
            $qb->andWhere(sprintf("%s.%s = :%s", 'i', $field, $field))
                ->setParameter($field, $value);

        try
        {
            $qb->getQuery()->getSingleResult(Query::HYDRATE_SCALAR);

            return true;
        }
        catch (NoResultException $exception)
        {
            return false;
        }
    }

}
