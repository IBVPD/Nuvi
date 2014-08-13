<?php

namespace NS\SentinelBundle\Repository;

use NS\SecurityBundle\Doctrine\SecuredEntityRepository;
use NS\UtilBundle\Service\AjaxAutocompleteRepositoryInterface;

/**
 * Description of Common
 *
 * @author gnat
 */
class Common extends SecuredEntityRepository implements AjaxAutocompleteRepositoryInterface
{
    public function getAllSecuredQueryBuilder($alias = 'o')
    {
        return $this->secure($this->createQueryBuilder($alias)->orderBy("$alias.name","ASC"));
    }

    public function getForAutoComplete($fields, array $value, $limit)
    {
        $alias = 'd';
        $qb    = $this->createQueryBuilder($alias)->setMaxResults($limit);

        if(!empty($value) && $value['value'][0]=='*')
            return $qb->getQuery();

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

    public function numberAndPercentEnrolledByAdmissionDiagnosis($alias = 'c')
    {
        $config = $this->_em->getConfiguration();
        $config->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

        $qb = $this->createQueryBuilder($alias)
                                ->select(sprintf('MONTH(%s.createdAt) as CreatedMonth,COUNT(%s.admDx) as admDxCount,%s.admDx',$alias,$alias,$alias))
                                ->where($alias.'.age <= :age')
                                ->setParameter('age',5)
                                ->groupBy($alias.'.admDx,CreatedMonth');

        return $this->secure($qb);
    }
}
