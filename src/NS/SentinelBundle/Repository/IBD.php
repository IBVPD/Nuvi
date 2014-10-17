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
use \NS\SentinelBundle\Repository\Common;

/**
 * Description of Common
 *
 * @author gnat
 */
class IBD extends Common
{
    public function getStats()
    {
        $results = array();
        $queryBuilder = $this->createQueryBuilder('m')
                   ->select('COUNT(m.id) theCount')
                   ->where('m.cxrDone = :cxr')
                   ->setParameter('cxr', TripleChoice::YES);

        $results['cxr'] = $this->secure($queryBuilder)->getQuery()->getSingleScalarResult();

        $queryBuilder = $this->createQueryBuilder('m')
                   ->select('m.csfCollected, COUNT(m.csfCollected) theCount')
                   ->groupBy('m.csfCollected');
        
        $res     = $this->secure($queryBuilder)->getQuery()->getResult();

        foreach($res as $r)
        {
            if($r['csfCollected'])
                $results['csfCollected'] = $r['theCount'];
            else
                $results['csfNotCollected'] = $r['theCount'];
        }
        
        return $results;
    }

    public function getLatestQuery($alias = 'm')
    {
        $queryBuilder = $this->createQueryBuilder($alias)
                   ->orderBy($alias.'.id','DESC');

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

    public function get($id)
    {
        $queryBuilder = $this->createQueryBuilder('m')
                   ->select('m,s,c,r')
                   ->innerJoin('m.site', 's')
                   ->innerJoin('s.country', 'c')
                   ->innerJoin('m.region', 'r')
                   ->where('m.id = :id')->setParameter('id',$id);
        try
        {
            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        }
        catch(NoResultException $e)
        {
            throw new NonExistentCase("This case does not exist!");
        }
    }

    public function search($id)
    {
        $queryBuilder = $this->createQueryBuilder('m')
                   ->where('m.id LIKE :id')
                   ->setParameter('id',"%$id%");

        return $this->secure($queryBuilder)->getQuery()->getResult();
    }

    public function checkExistence($id)
    {
        try 
        {
            $queryBuilder = $this->createQueryBuilder('m')
                       ->where('m.id = :id')
                       ->setParameter('id', $id);

            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        }
        catch(NoResultException $e)
        {
            throw new NonExistentCase("This case does not exist!");
        }
    }

    public function find($id)
    {
        try
        {
            $queryBuilder = $this->createQueryBuilder('m')
                       ->addSelect('r,c,s')
                       ->leftJoin('m.region', 'r')
                       ->leftJoin('m.country', 'c')
                       ->leftJoin('m.site', 's')
                       ->andWhere('m.id = :id')
                       ->setParameter('id', $id);

            return $this->secure($queryBuilder)->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD,true)->getSingleResult();
        }
        catch(NoResultException $e)
        {
            return null; //throw new NonExistentCase("This case does not exist!");
        }
    }

    public function exportQuery($alias)
    {
        return $this->secure(
                        $this->createQueryBuilder($alias)
                             ->select($alias.',s,c,r')
                             ->innerJoin($alias.'.site', 's')
                             ->innerJoin($alias.'.country', 'c')
                             ->innerJoin($alias.'.region', 'r')
                            );
    }

    public function getFilterQueryBuilder($alias = 'm')
    {
        return $this->getLatestQuery($alias);
    }

    public function findModified($modifiedSince = null)
    {
        $queryBuilder = $this->getLatestQuery('m');

        if($modifiedSince)
            $queryBuilder->where('m.updatedAt >= :updatedAt')->setParameter ('updatedAt', $modifiedSince);

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
                   ->select(sprintf('YEAR(%s.admDate) as theYear, COUNT(%s.id) as theCount,%s.ageDistribution',$alias,$alias,$alias))
                   ->where(sprintf('(%s.result = :suspectedMening)',$alias))
                   ->setParameter('suspectedMening', IBDCaseResult::PROBABLE)
                   ->groupBy(sprintf('theYear,%s.ageDistribution',$alias))
                   ->orderBy('theYear','ASC')
                ;

        return $this->secure($queryBuilder);
    }

    private function getCountQueryBuilder($alias, array $siteCodes)
    {
        $queryBuilder    = $this->createQueryBuilder($alias)->innerJoin($alias.'.site','s')->groupBy($alias.'.site');
        $where = $params = array();
        $x     = 0;

        if(empty($siteCodes))
            return $queryBuilder;

        foreach($siteCodes as $site)
        {
            $where[] = "$alias.site = :site$x";
            $params['site'.$x] = $site;
            $x++;
        }

        return $queryBuilder->where("(".implode(" OR ",$where).")")->setParameters($params);
    }

    public function getCsfCollectedCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.csfCollected) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfCollected = :csfCollected',$alias))
                    ->setParameter('csfCollected', TripleChoice::YES);
    }

    public function getBloodCollectedCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.bloodCollected) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.bloodCollected = :bloodCollected',$alias))
                    ->setParameter('bloodCollected', TripleChoice::YES);
    }

//replace bloodresult=1 if  blood_gram_stain!=. & blood_gram_stain!=99
//replace bloodresult=1 if  blood_gram_result!=. & blood_gram_result!=99
//replace bloodresult=1 if  blood_PCR_result!=. & blood_PCR_result!=99
//replace bloodresult=0 if bloodresult==.
    public function getBloodResultCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('(%s.bloodCultResult != :unknown OR %s.bloodGramResult != :unknown OR %s.bloodPcrResult != :unknown )',$alias,$alias,$alias))
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfResultCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfCultResult != :unknown',$alias))
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfBinaxResultCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfBinaxResult != :unknown',$alias))
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfBinaxDoneCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfBinaxDone = :yes',$alias))
                    ->setParameter('yes', TripleChoice::YES);
    }

    public function getCsfLatResultCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfLatResult != :unknown',$alias))
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfLatDoneCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfLatDone = :yes',$alias))
                    ->setParameter('yes', TripleChoice::YES);
    }

    public function getCsfPcrCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfPcrResult != :unknown',$alias))
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfSpnCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('(%s.spnSerotype != :other OR %s.spnSerotype != :notDone)',$alias,$alias))
                    ->setParameter('other', SpnSerotype::OTHER)
                    ->setParameter('notDone', SpnSerotype::_NOT_DONE);
    }

    public function getCsfHiCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('(%s.hiSerotype != :other OR %s.hiSerotype != :notDone)',$alias,$alias))
                    ->setParameter('other', HiSerotype::OTHER)
                    ->setParameter('notDone', HiSerotype::NOT_DONE);
    }

    public function getPcrPositiveCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias,$siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfPcrResult = :spn',$alias))
                    ->setParameter('spn', PCRResult::SPN);
    }

    public function getCountByCulture($alias, $culture, $binax = null, $pcr = null, array $siteCodes = array())
    {
        $this->_em->getConfiguration()->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');

        $queryBuilder = $this->getCountQueryBuilder($alias,$siteCodes)
                   ->select(sprintf('%s.id,COUNT(%s.id) as caseCount, s.code, YEAR(%s.admDate) as theYear',$alias, $alias, $alias));

        if($culture)
        {
            $queryBuilder->andWhere(sprintf(' ( %s.csfCultResult = :spn OR %s.csfCultResult = :hi OR %s.csfCultResult = :nm) ',$alias,$alias,$alias))
               ->setParameter('spn', CultureResult::SPN)
               ->setParameter('hi', CultureResult::HI)
               ->setParameter('nm', CultureResult::NM);
        }
        else
        {
            $queryBuilder->andWhere(sprintf(' ( %s.csfCultResult = :negative ) ',$alias))
               ->setParameter('negative', CultureResult::NEGATIVE);
        }

        if(!is_null($binax))
        {
            $queryBuilder->andWhere(sprintf(' ( %s.csfBinaxResult = :binax ) ',$alias));
            if($binax)
                $queryBuilder->setParameter('binax', BinaxResult::POSITIVE);
            else
                $queryBuilder->setParameter('binax', BinaxResult::NEGATIVE);
        }

        if(!is_null($pcr))
        {
            if($pcr)
                $queryBuilder->andWhere(sprintf(' ( %s.csfPcrResult != :pcrNegative AND %s.csfPcrResult != :pcrContaminant AND %s.csfPcrResult != :pcrUnknown ) ',$alias,$alias,$alias))
                             ->setParameter('pcrNegative', CultureResult::NEGATIVE)
                             ->setParameter('pcrUnknown', CultureResult::UNKNOWN)
                             ->setParameter('pcrContaminant', CultureResult::CONTAMINANT);
            else
                $queryBuilder->andWhere(sprintf(' ( %s.csfPcrResult = :pcr ) ',$alias))
                             ->setParameter('pcr', CultureResult::NEGATIVE);
        }

        return $queryBuilder;
    }

    public function exists(array $params)
    {
        $qb = $this->createQueryBuilder('i')->select('i.id');
        foreach($params as $field=>$value)
            $qb->andWhere(sprintf("%s.%s = :%s",'i',$field,$field))
               ->setParameter ($field, $value);

        try
        {
            $qb->getQuery()->getSingleResult(Query::HYDRATE_SCALAR);

            return true;
        }
        catch(\Doctrine\ORM\NoResultException $e)
        {
            return false;
        }
    }
}
