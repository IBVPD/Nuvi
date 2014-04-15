<?php

namespace NS\SentinelBundle\Repository;

use NS\SecurityBundle\Doctrine\SecuredEntityRepository;
use NS\UtilBundle\Service\AjaxAutocompleteRepositoryInterface;
use Doctrine\ORM\Query;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
use \Doctrine\ORM\NoResultException;

/**
 * Description of Common
 *
 * @author gnat
 */
class Meningitis extends SecuredEntityRepository implements AjaxAutocompleteRepositoryInterface
{
    public function getStats(\DateTime $start = null, \DateTime $end = null)
    {
        $results = array();
        $qb      = $this->_em
                   ->createQueryBuilder()
                   ->select('COUNT(m.id) theCount')
                   ->from($this->getClassName(),'m')
                   ->innerJoin('m.lab', 'sl')
                   ->where('sl.cxrDone = :cxr')
                   ->setParameter('cxr', \NS\SentinelBundle\Form\Types\TripleChoice::YES);

        $results['cxr'] = $this->secure($qb)->getQuery()->getSingleScalarResult();

        $qb      = $this->_em
                   ->createQueryBuilder()
                   ->select('m.csfCollected, COUNT(m.csfCollected) theCount')
                   ->from($this->getClassName(),'m')
//                   ->innerJoin('m.lab', 'sl')
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
        $qb    = $this->_em->createQueryBuilder()
                              ->select($alias)
                              ->from($this->getClassName(), $alias)
                              ->setMaxResults($limit);

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

    public function getLatestQuery()
    {
        $qb = $this->_em->createQueryBuilder()
                   ->select('m,l,rl')
                   ->from($this->getClassName(),'m')
                   ->leftJoin('m.lab', 'l')
                   ->leftJoin('m.externalLabs','rl')
                   ->orderBy('m.id','DESC');
        return $this->secure($qb);
    }

    public function getLatest($limit = 10)
    {
        $qb = $this->_em->createQueryBuilder()
                   ->select('m,l')
                   ->from($this->getClassName(),'m')
                   ->leftJoin('m.lab', 'l')
                   ->orderBy('m.id','DESC')
                   ->setMaxResults($limit);
        return $this->secure($qb)->getQuery()->getResult();
    }
    
    public function getByCountry()
    {
        $qb = $this->_em->createQueryBuilder()
                   ->select('COUNT(m) as numberOfCases, partial m.{id,admDate}, c')
                   ->from($this->getClassName(),'m')
                   ->innerJoin('m.country', 'c')
                   ->groupBy('m.country');

        return $this->secure($qb)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getByDiagnosis()
    {
        $qb = $this->_em->createQueryBuilder()
                   ->select('COUNT(m) as numberOfCases, partial m.{id,dischDx}')
                   ->from($this->getClassName(),'m')
                   ->groupBy('m.dischDx');

        return $this->secure($qb)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function getBySite()
    {
        $qb = $this->_em->createQueryBuilder()
                   ->select('COUNT(m) as numberOfCases, partial m.{id,admDate}, s ')
                   ->from($this->getClassName(),'m')
                   ->innerJoin('m.site', 's')
                   ->groupBy('m.site');

        return $this->secure($qb)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    public function get($id)
    {
        $qb = $this->createQueryBuilder('m')
                   ->select('m,s,c,r,e,l')
                   ->innerJoin('m.site', 's')
                   ->innerJoin('s.country', 'c')
                   ->innerJoin('m.region', 'r')
                   ->leftJoin('m.externalLabs', 'e')
                   ->leftJoin('m.lab','l')
                   ->where('m.id = :id')->setParameter('id',$id);

        return $this->secure($qb)->getQuery()->getSingleResult();
    }

    public function search($id)
    {
        $qb = $this->_em->createQueryBuilder()
                        ->select('m')
                        ->from($this->getClassName(),'m')
                        ->where('m.id LIKE :id')->setParameter('id',"%$id%");

        return $this->secure($qb)->getQuery()->getResult();
    }

    public function checkExistence($id)
    {
        try 
        {
            $qb = $this->_em
                      ->createQueryBuilder('m')
                      ->select('m')
                      ->from($this->getClassName(),'m')
                      ->where('m.id = :id')
                      ->setParameter('id', $id);
            
            if($this->hasSecuredQuery())
                return $this->secure($qb)
                            ->getQuery()
                            ->getSingleResult();
            else
                return $qb->getQuery()->getSingleResult();
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
            $qb = $this->_em
                       ->createQueryBuilder()
                       ->select('m')
                       ->from($this->getClassName(),'m')
                       ->where('m.id = :id')
                       ->setParameter('id', $id);

            if($this->hasSecuredQuery())
                return $this->secure($qb)->getQuery()->getSingleResult();
            else
                return $qb->getQuery()->getSingleResult();
        }
        catch(NoResultException $e)
        {
            throw new NonExistentCase("This case does not exist!");
        }
    }

    public function getFilterQueryBuilder($alias = 'm')
    {
        return $this->secure($this->_em
                    ->createQueryBuilder()
                    ->select("$alias,rl,l")
                    ->from($this->getClassName(),$alias)
                    ->leftJoin("$alias.externalLabs", "rl")
                    ->leftJoin("$alias.lab",'l')
                    ->orderBy('m.id','DESC'))
                    ;
    }

    public function findModified($modifiedSince = null)
    {
        $qb = $this->createQueryBuilder('m')
                    ->select('m,rl,l')
                    ->leftJoin("m.externalLabs", "rl")
                    ->leftJoin("m.lab",'l')
                    ->orderBy('m.id','DESC')
                ;

        if($modifiedSince)
            $qb->where('m.updatedAt >= :updatedAt')->setParameter ('updatedAt', $modifiedSince);

        return $qb->getQuery()->getResult();
    }
}
