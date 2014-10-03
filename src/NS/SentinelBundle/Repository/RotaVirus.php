<?php

namespace NS\SentinelBundle\Repository;

use NS\UtilBundle\Service\AjaxAutocompleteRepositoryInterface;
use Doctrine\ORM\Query;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
use \Doctrine\ORM\NoResultException;
use \NS\SentinelBundle\Repository\Common;

/**
 * Description of Common
 *
 * @author gnat
 */
class RotaVirus extends Common implements AjaxAutocompleteRepositoryInterface
{
    public function getStats()
    {
        $results = array();
        $queryBuilder = $this->_em
                   ->createQueryBuilder()
                   ->select('COUNT(m.id) theCount')
                   ->from($this->getClassName(),'m')
                   ->where('m.cxrDone = :cxr')
                   ->setParameter('cxr', \NS\SentinelBundle\Form\Types\TripleChoice::YES);

        $results['cxr'] = $this->secure($queryBuilder)->getQuery()->getSingleScalarResult();

        $queryBuilder = $this->_em
                   ->createQueryBuilder()
                   ->select('m.csfCollected, COUNT(m.csfCollected) theCount')
                   ->from($this->getClassName(),'m')
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

    public function getLatestQuery( $alias = 'm')
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
        $queryBuilder = $this->_em->createQueryBuilder()
                   ->select('COUNT(m) as numberOfCases, partial m.{id,admDate}, c')
                   ->from($this->getClassName(),'m')
                   ->innerJoin('m.country', 'c')
                   ->groupBy('m.country');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getByDiagnosis()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
                   ->select('COUNT(m) as numberOfCases, partial m.{id,dischDx}')
                   ->from($this->getClassName(),'m')
                   ->groupBy('m.dischDx');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getBySite()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
                   ->select('COUNT(m) as numberOfCases, partial m.{id,admDate}, s ')
                   ->from($this->getClassName(),'m')
                   ->innerJoin('m.site', 's')
                   ->groupBy('m.site');

        return $this->secure($queryBuilder)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function get($id)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
                        ->select('m,s,c,r')
                        ->from($this->getClassName(),'m')
                        ->innerJoin('m.site', 's')
                        ->innerJoin('s.country', 'c')
                        ->innerJoin('m.region', 'r')
                        ->where('m.id = :id')->setParameter('id',$id);

        try {
            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        }
        catch(NoResultException $e)
        {
            throw new NonExistentCase("This case does not exist!");
        }
    }

    public function search($id)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
                        ->select('m')
                        ->from($this->getClassName(),'m')
                        ->where('m.id LIKE :id')->setParameter('id',"%$id%");

        return $this->secure($queryBuilder)->getQuery()->getResult();
    }

    public function checkExistence($id)
    {
        try 
        {
            $queryBuilder = $this->_em
                       ->createQueryBuilder('m')
                       ->select('m')
                       ->from($this->getClassName(),'m')
                       ->where('m.id = :id')
                       ->setParameter('id', $id);
            
            if($this->hasSecuredQuery())
                return $this->secure($queryBuilder)
                            ->getQuery()
                            ->getSingleResult();
            else
                return $queryBuilder->getQuery()->getSingleResult();
        }
        catch(NoResultException $e)
        {
            throw new NonExistentCase("This case does not exist!");
        }
    }

    public function findOrCreate($caseId, $id = null)
    {
        if($id == null && $caseId == null)
            throw new \InvalidArgumentException("Id or Case must be provided");

        $queryBuilder = $this->createQueryBuilder('m')
                   ->select('m,s,c,r')
                   ->innerJoin('m.site', 's')
                   ->innerJoin('s.country', 'c')
                   ->innerJoin('m.region', 'r')
                   ->where('m.caseId = :caseId')
                   ->setParameter('caseId', $caseId);

        if($id)
            $queryBuilder->orWhere('m.id = :id')->setParameter('id', $id);

        try
        {
            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        }
        catch (NoResultException $ex)
        {
            $res = new \NS\SentinelBundle\Entity\RotaVirus();
            $res->setCaseId($caseId);

            return $res;
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

            $queryBuilder = ($this->hasSecuredQuery()) ? $this->secure($queryBuilder): $queryBuilder;

            return $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD,true)->getSingleResult();
        }
        catch(NoResultException $e)
        {
            throw new NonExistentCase("This case does not exist!");
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
        return $this->secure($this->createQueryBuilder($alias)->orderBy($alias.'.id','DESC'));
    }
}
