<?php

namespace NS\SentinelBundle\Repository;

use \DateTime;
use \Doctrine\ORM\NoResultException;
use \Doctrine\ORM\Query;
use \InvalidArgumentException;
use \NS\SentinelBundle\Entity\IBD as C;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
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

    private function getCountQueryBuilder($alias, array $siteCodes)
    {
        $qb    = $this->createQueryBuilder($alias)->innerJoin($alias.'.site','s')->groupBy($alias.'.site');
        $where = $params = array();
        $x     = 0;

        foreach($siteCodes as $site)
        {
            $where[] = "$alias.site = :site$x";
            $params['site'.$x] = $site;
            $x++;
        }

        return $qb->where("(".implode(" OR ",$where).")")->setParameters($params);
    }

    public function getCsfCollectedCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.csfCollected) as csfCollectedCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfCollected = :csfCollected',$alias))
                    ->setParameter('csfCollected', TripleChoice::YES);
    }

    public function getBloodCollectedCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.bloodCollected) as bloodCollectedCount,s.code',$alias,$alias))
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
                    ->select(sprintf('%s.id,COUNT(%s.id) as bloodResultCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('(%s.bloodCultResult != :unknown OR %s.bloodGramResult != :unknown OR %s.bloodPcrResult != :unknown )',$alias,$alias,$alias))
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfResultCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as csfResultCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfCultResult != :unknown',$alias))
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfBinaxResultCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as csfBinaxResult,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfBinaxResult != :unknown',$alias))
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfBinaxDoneCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as csfBinaxDone,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfBinaxDone = :yes',$alias))
                    ->setParameter('yes', TripleChoice::YES);
    }

    public function getCsfLatResultCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as csfLatResult,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfLatResult != :unknown',$alias))
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfLatDoneCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as csfLatDone,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfLatDone = :yes',$alias))
                    ->setParameter('yes', TripleChoice::YES);
    }

    public function getCsfPcrCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as csfPcrResultCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfPcrResult != :unknown',$alias))
                    ->setParameter('unknown', TripleChoice::UNKNOWN);
    }

    public function getCsfSpnCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as csfSpnResultCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('(%s.spnSerotype != :other OR %s.spnSerotype != :notDone)',$alias,$alias))
                    ->setParameter('other', SpnSerotype::OTHER)
                    ->setParameter('notDone', SpnSerotype::_NOT_DONE);
    }

    public function getCsfHiCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as csfHiResultCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('(%s.hiSerotype != :other OR %s.hiSerotype != :notDone)',$alias,$alias))
                    ->setParameter('other', HiSerotype::OTHER)
                    ->setParameter('notDone', HiSerotype::NOT_DONE);
    }

    public function getPcrPositiveCountBySites($alias, array $siteCodes)
    {
        return $this->getCountQueryBuilder($alias,$siteCodes)
                    ->select(sprintf('%s.id,COUNT(%s.id) as pcrPositiveCount,s.code',$alias,$alias))
                    ->andWhere(sprintf('%s.csfPcrResult = :spn',$alias))
                    ->setParameter('spn', PCRResult::SPN);
    }
}
