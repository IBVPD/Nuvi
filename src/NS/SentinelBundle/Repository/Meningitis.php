<?php

namespace NS\SentinelBundle\Repository;

use NS\SecurityBundle\Doctrine\SecuredEntityRepository;
use NS\UtilBundle\Service\AjaxAutocompleteRepositoryInterface;
use Doctrine\ORM\Query;

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
                   ->where('m.cxrDone = :cxr')
                   ->setParameter('cxr', \NS\SentinelBundle\Form\Types\TripleChoice::YES);

        $results['cxr'] = $this->secure($qb)->getQuery()->getSingleScalarResult();

        $qb      = $this->_em
                   ->createQueryBuilder()
                   ->select('m.csfCollected, COUNT(m.csfCollected) theCount')
                   ->from($this->getClassName(),'m')
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
    
    public function getLatest($limit = 10)
    {
        $qb = $this->_em->createQueryBuilder()
                   ->select('m')
                   ->from($this->getClassName(),'m')
//                   ->orderBy('m.created','DESC')
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
    
    public function getBySite()
    {
        $qb = $this->_em->createQueryBuilder()
                   ->select('COUNT(m) as numberOfCases, partial m.{id,admDate}, s ')
                   ->from($this->getClassName(),'m')
                   ->innerJoin('m.site', 's')
                   ->groupBy('m.site');

        return $this->secure($qb)->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }
}
