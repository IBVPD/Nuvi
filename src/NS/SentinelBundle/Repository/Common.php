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
        return $this->secure($this->createQueryBuilder($alias)->orderBy("$alias.name", "ASC"));
    }

    public function getForAutoComplete($fields, array $value, $limit)
    {
        $alias        = 'd';
        $queryBuilder = $this->createQueryBuilder($alias)->setMaxResults($limit);

        if (!empty($value) && $value['value'][0] == '*')
            return $queryBuilder->getQuery();

        if (!empty($value)) {
            if (is_array($fields)) {
                foreach ($fields as $f) {
                    $field = "$alias.$f";
                    $queryBuilder->addOrderBy($field)
                        ->orWhere("$field LIKE :param")->setParameter('param', $value['value'] . '%');
                }
            }
            else {
                $field = "$alias.$fields";
                $queryBuilder->orderBy($field)->andWhere("$field LIKE :param")->setParameter('param', $value['value'] . '%');
            }
        }

        return $queryBuilder->getQuery();
    }

    public function findOrCreate($caseId, $objId = null)
    {
        if ($objId == null && $caseId == null)
            throw new InvalidArgumentException("Id or Case must be provided");

        $queryBuilder = $this->createQueryBuilder('m')
            ->select('m,s,c,r')
            ->innerJoin('m.site', 's')
            ->innerJoin('s.country', 'c')
            ->innerJoin('m.region', 'r')
            ->where('m.caseId = :caseId')
            ->setParameter('caseId', $caseId);

        if ($objId)
            $queryBuilder->orWhere('m.id = :id')->setParameter('id', $objId);

        try {
            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        }
        catch (NoResultException $exception) {
            $cls = $this->getClassName();
            $res = new $cls();
            $res->setCaseId($caseId);

            return $res;
        }
    }

    public function findWithRelations(array $params)
    {
        $qb = $this->createQueryBuilder('c')
            ->addSelect('sl')
            ->leftJoin('c.siteLab', 'sl');

        foreach ($params as $field => $value) {
            $param = sprintf("%sField", $field);
            $qb->andWhere(sprintf('c.%s = :%s', $field, $param))->setParameter($param, $value);
        }
        try {
            return $qb->getQuery()->getSingleResult();
        }
        catch (\Exception $e) {
            return null;
        }
    }

}
