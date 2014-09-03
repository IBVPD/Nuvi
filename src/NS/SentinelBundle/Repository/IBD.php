<?php

namespace NS\SentinelBundle\Repository;

use DateTime;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use InvalidArgumentException;
use NS\SentinelBundle\Entity\IBD as C;
use NS\SentinelBundle\Exceptions\NonExistentCase;
use NS\SentinelBundle\Form\Types\IBDCaseResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Repository\Common;

/**
 * Description of Common
 *
 * @author gnat
 */
class IBD extends Common
{
    public function getStats(DateTime $start = null, DateTime $end = null)
    {
        $results = array();
        $qb      = $this->createQueryBuilder('m')
                   ->select('COUNT(m.id) theCount')
                   ->where('m.cxrDone = :cxr')
                   ->setParameter('cxr', TripleChoice::YES);

        $results['cxr'] = $this->secure($qb)->getQuery()->getSingleScalarResult();

        $qb      = $this->createQueryBuilder('m')
                   ->select('m.csfCollected, COUNT(m.csfCollected) theCount')
                   ->groupBy('m.csfCollected');
        
        $res     = $this->secure($qb)->getQuery()->getResult();

        foreach($res as $r)
        {
            if($r['csfCollected'])
                $results['csfCollected'] = $r['theCount'];
            else
                $results['csfNotCollected'] = $r['theCount'];
        }
        
        return $results;
    }

    public function getForAutoComplete($fields, array $value, $limit)
    {
        $alias = 'd';
        $qb    = $this->createQueryBuilder($alias)->setMaxResults($limit);

        if(!empty($value) && $value['value'][0]=='*') {
            return $qb->getQuery();
        }
        
        if(!empty($value))
        {
            if(is_array($fields))
            {
                foreach ($fields as $f)
                {
                    $field = "$alias.$f";
                    $qb->addOrderBy($field)
                       ->orWhere("$field LIKE :param")->setParameter('param',$value['value'].'%');
                }
            }
            else
            {
                $field = "$alias.$fields";
                $qb->orderBy($field)->andWhere("$field LIKE :param")->setParameter('param',$value['value'].'%');
            }
        }

        return $qb->getQuery();        
    }

    public function getLatestQuery($alias = 'm')
    {
        $qb = $this->createQueryBuilder($alias)
                   ->orderBy($alias.'.id','DESC');

        return $this->secure($qb);
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
        $qb = $this->createQueryBuilder('m')
                   ->select('COUNT(m) as numberOfCases, partial m.{id,admDate}, c')
                   ->innerJoin('m.country', 'c')
                   ->groupBy('m.country');

        return $this->secure($qb)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getByDiagnosis()
    {
        $qb = $this->createQueryBuilder('m')
                   ->select('COUNT(m) as numberOfCases, partial m.{id,dischDx}')
                   ->groupBy('m.dischDx');

        return $this->secure($qb)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getBySite()
    {
        $qb = $this->createQueryBuilder('m')
                   ->select('COUNT(m) as numberOfCases, partial m.{id,admDate}, s ')
                   ->innerJoin('m.site', 's')
                   ->groupBy('m.site');

        return $this->secure($qb)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function get($id)
    {
        $qb = $this->createQueryBuilder('m')
                   ->select('m,s,c,r')
                   ->innerJoin('m.site', 's')
                   ->innerJoin('s.country', 'c')
                   ->innerJoin('m.region', 'r')
                   ->where('m.id = :id')->setParameter('id',$id);
        try
        {
            return $this->secure($qb)->getQuery()->getSingleResult();
        }
        catch(NoResultException $e)
        {
            throw new NonExistentCase("This case does not exist!");
        }
    }

    public function search($id)
    {
        $qb = $this->createQueryBuilder('m')
                   ->where('m.id LIKE :id')
                   ->setParameter('id',"%$id%");

        return $this->secure($qb)->getQuery()->getResult();
    }

    public function checkExistence($id)
    {
        try 
        {
            $qb = $this->createQueryBuilder('m')
                       ->where('m.id = :id')
                       ->setParameter('id', $id);

            return $this->secure($qb)->getQuery()->getSingleResult();
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
            $qb = $this->createQueryBuilder('m')
                       ->addSelect('r,c,s')
                       ->leftJoin('m.region', 'r')
                       ->leftJoin('m.country', 'c')
                       ->leftJoin('m.site', 's')
                       ->andWhere('m.id = :id')
                       ->setParameter('id', $id);

            return $this->secure($qb)->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD,true)->getSingleResult();
        }
        catch(NoResultException $e)
        {
            return null; //throw new NonExistentCase("This case does not exist!");
        }
    }

    public function findOrCreate($caseId, $id = null)
    {
        if($id == null && $caseId == null)
            throw new InvalidArgumentException("Id or Case must be provided");

        $qb = $this->createQueryBuilder('m')
                   ->select('m,s,c,r')
                   ->innerJoin('m.site', 's')
                   ->innerJoin('s.country', 'c')
                   ->innerJoin('m.region', 'r')
                   ->where('m.caseId = :caseId')
                   ->setParameter('caseId', $caseId);

        if($id)
            $qb->orWhere('m.id = :id')->setParameter('id', $id);

        try
        {
            return $this->secure($qb)->getQuery()->getSingleResult();
        }
        catch (NoResultException $ex)
        {
            $res = new C();
            $res->setCaseId($caseId);

            return $res;
        }
    }

    public function getFilterQueryBuilder($alias = 'm')
    {
        return $this->getLatestQuery($alias);
    }

    public function findModified($modifiedSince = null)
    {
        $qb = $this->getLatestQuery('m');

        if($modifiedSince)
            $qb->where('m.updatedAt >= :updatedAt')->setParameter ('updatedAt', $modifiedSince);

        return $qb->getQuery()->getResult();
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

        $qb = $this->createQueryBuilder($alias)
                   ->select(sprintf('YEAR(%s.createdAt) as theYear,%s',$alias,$alias))
                   ->where(sprintf('(%s.result = :suspectedMening)',$alias))
                   ->setParameter('suspectedMening', IBDCaseResult::PROBABLE)
                   ->orderBy('theYear','ASC')
                ;

        return $this->secure($qb);
    }

    private function getCountQueryBuilder(array $siteCodes,\DateTime $from, \DateTime $to)
    {
        $qb    = $this->createQueryBuilder('i')->innerJoin('i.site','s')->groupBy('i.site');
        $where = $params = array();
        $x     = 0;

        foreach($siteCodes as $site)
        {
            $where[] = "i.site = :site$x";
            $params['site'.$x] = $site;
            $x++;
        }

        $qb->where("(".implode(" OR ",$where).") AND i.admDate BETWEEN :from AND :to")
           ->setParameters(array_merge($params,array('from'=>$from,'to'=>$to)));

        return $qb;

    }

    public function getCsfCollectedCountBySites(array $siteCodes,\DateTime $from, \DateTime $to)
    {
        return $this->getCountQueryBuilder($siteCodes,$from,$to)
                    ->select('i.id,COUNT(i.csfCollected) as csfCollectedCount,s.code')
                    ->andWhere("i.csfCollected = :csfCollected")
                    ->setParameter('csfCollected', TripleChoice::YES);
    }

    public function getBloodCollectedCountBySites(array $siteCodes,\DateTime $from, \DateTime $to)
    {
        return $this->getCountQueryBuilder($siteCodes,$from,$to)
                    ->select('i.id,COUNT(i.bloodCollected) as bloodCollectedCount,s.code')
                    ->andWhere("i.bloodCollected = :bloodCollected")
                    ->setParameter('bloodCollected', TripleChoice::YES);
    }

//replace bloodresult=1 if  blood_gram_stain!=. & blood_gram_stain!=99
//replace bloodresult=1 if  blood_gram_result!=. & blood_gram_result!=99
//replace bloodresult=1 if  blood_PCR_result!=. & blood_PCR_result!=99
//replace bloodresult=0 if bloodresult==.
    public function getBloodResultCountBySites(array $siteCodes,\DateTime $from, \DateTime $to)
    {
        return $this->getCountQueryBuilder($siteCodes,$from,$to)
                    ->select('i.id,COUNT(i.id) as bloodResultCount,s.code')
                    ->andWhere("(i.bloodCultResult != :unknown OR i.bloodGramResult != :unknown OR i.bloodPcrResult != :unknown )")
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfResultCountBySites(array $siteCodes,\DateTime $from, \DateTime $to)
    {
        return $this->getCountQueryBuilder($siteCodes,$from,$to)
                    ->select('i.id,COUNT(i.id) as csfResultCount,s.code')
                    ->andWhere("i.csfCultResult != :unknown")
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfBinaxResultCountBySites(array $siteCodes,\DateTime $from, \DateTime $to)
    {
        return $this->getCountQueryBuilder($siteCodes,$from,$to)
                    ->select('i.id,COUNT(i.id) as csfBinaxResult,s.code')
                    ->andWhere("i.csfBinaxResult != :unknown")
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfBinaxDoneCountBySites(array $siteCodes,\DateTime $from, \DateTime $to)
    {
        return $this->getCountQueryBuilder($siteCodes,$from,$to)
                    ->select('i.id,COUNT(i.id) as csfBinaxDone,s.code')
                    ->andWhere("i.csfBinaxDone = :yes")
                    ->setParameter('yes', TripleChoice::YES);
    }

    public function getCsfLatResultCountBySites(array $siteCodes,\DateTime $from, \DateTime $to)
    {
        return $this->getCountQueryBuilder($siteCodes,$from,$to)
                    ->select('i.id,COUNT(i.id) as csfLatResult,s.code')
                    ->andWhere("i.csfLatResult != :unknown")
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfLatDoneCountBySites(array $siteCodes,\DateTime $from, \DateTime $to)
    {
        return $this->getCountQueryBuilder($siteCodes,$from,$to)
                    ->select('i.id,COUNT(i.id) as csfLatDone,s.code')
                    ->andWhere("i.csfLatDone = :yes")
                    ->setParameter('yes', TripleChoice::YES);
    }
}
