<?php

namespace NS\SentinelBundle\Repository;

use \Doctrine\ORM\NoResultException;
use \Doctrine\ORM\QueryBuilder;
use \NS\SecurityBundle\Doctrine\SecuredEntityRepository;
use \NS\UtilBundle\Service\AjaxAutocompleteRepositoryInterface;

/**
 * Description of Common
 *
 * @author gnat
 */
class Common extends SecuredEntityRepository implements AjaxAutocompleteRepositoryInterface
{
    public function secure(QueryBuilder $queryBuilder)
    {
        return $this->hasSecuredQuery() ? parent::secure($queryBuilder) : $queryBuilder;
    }

    public function getAllSecuredQueryBuilder($alias = 'o')
    {
        return $this->secure($this->createQueryBuilder($alias)->orderBy("$alias.name","ASC"));
    }

    public function getForAutoComplete($fields, array $value, $limit)
    {
        $alias = 'd';
        $queryBuilder = $this->createQueryBuilder($alias)->setMaxResults($limit);

        if(!empty($value) && $value['value'][0]=='*')
            return $queryBuilder->getQuery();

        if(!empty($value))
        {
            if(is_array($fields))
            {
                foreach ($fields as $f)
                {
                    $field = "$alias.$f";
                    $queryBuilder->addOrderBy($field)
                       ->orWhere("$field LIKE :param")->setParameter('param',$value['value'].'%');
                }
            }
            else
            {
                $field = "$alias.$fields";
                $queryBuilder->orderBy($field)->andWhere("$field LIKE :param")->setParameter('param',$value['value'].'%');
            }
        }

        return $queryBuilder->getQuery();
    }

    public function numberAndPercentEnrolledByAdmissionDiagnosis($alias = 'c', $ageInMonths = 59)
    {
        $config = $this->_em->getConfiguration();
        $config->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

        $queryBuilder = $this->createQueryBuilder($alias)
                                ->select(sprintf('MONTH(%s.admDate) as AdmissionMonth,COUNT(%s.admDx) as admDxCount,%s.admDx',$alias,$alias,$alias))
                                ->where(sprintf("(%s.admDx IS NOT NULL AND %s.age <= :age)",$alias,$alias))
                                ->setParameter('age',$ageInMonths)
                                ->groupBy($alias.'.admDx,AdmissionMonth');

        return $this->secure($queryBuilder);
    }

    public function findOrCreate($caseId, $id = null)
    {
        if($id == null && $caseId == null)
            throw new InvalidArgumentException("Id or Case must be provided");

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
            $cls = $this->getClassName();
            $res = new $cls();
            $res->setCaseId($caseId);

            return $res;
        }
    }
}
