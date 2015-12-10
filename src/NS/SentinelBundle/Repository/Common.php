<?php

namespace NS\SentinelBundle\Repository;


use \Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use \Doctrine\ORM\QueryBuilder;
use NS\ImportBundle\Exceptions\DuplicateCaseException;
use \NS\SecurityBundle\Doctrine\SecuredEntityRepository;
use NS\SentinelBundle\Exceptions\InvalidCaseException;
use \NS\UtilBundle\Service\AjaxAutocompleteRepositoryInterface;

/**
 * Description of Common
 *
 * @author gnat
 */
class Common extends SecuredEntityRepository implements AjaxAutocompleteRepositoryInterface
{
    /**
    * @param QueryBuilder $queryBuilder
    * @return QueryBuilder
     */
    public function secure(QueryBuilder $queryBuilder)
    {
        return $this->hasSecuredQuery() ? parent::secure($queryBuilder) : $queryBuilder;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->getAllSecuredQueryBuilder()->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD,true)->getResult();
    }

    /**
     * @param string $alias
     * @return QueryBuilder
     */
    public function getAllSecuredQueryBuilder($alias = 'o')
    {
        return $this->secure($this->_em
            ->createQueryBuilder($alias)
            ->select($alias)
            ->from($this->getClassName(),$alias,sprintf('%s.code',$alias))
            ->orderBy($alias.'.name', 'ASC'));
    }

    /**
     * @param $fields
     * @param array $value
     * @param $limit
     * @return \Doctrine\ORM\Query
     */
    public function getForAutoComplete($fields, array $value, $limit)
    {
        $alias = 'd';
        $queryBuilder = $this->createQueryBuilder($alias)->setMaxResults($limit);

        if (!empty($value) && $value['value'][0] == '*') {
            return $queryBuilder->getQuery();
        }

        if (!empty($value)) {
            if (is_array($fields)) {
                foreach ($fields as $f) {
                    $field = "$alias.$f";
                    $queryBuilder->addOrderBy($field)
                        ->orWhere("$field LIKE :param")->setParameter('param', '%'.$value['value'].'%');
                }
            } else {
                $field = "$alias.$fields";
                $queryBuilder->orderBy($field)->andWhere("$field LIKE :param")->setParameter('param', '%'.$value['value'].'%');
            }
        }

        return $queryBuilder->getQuery();
    }

    /**
     * @param $caseId
     * @param null $objId
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     */
    public function findOrCreate($caseId, $objId = null)
    {
        if ($objId === null && $caseId === null) {
            throw new \InvalidArgumentException("Id or Case must be provided");
        }

        $queryBuilder = $this->createQueryBuilder('m')
            ->select('m,s,c,r')
            ->innerJoin('m.site', 's')
            ->innerJoin('s.country', 'c')
            ->innerJoin('m.region', 'r')
            ->where('m.caseId = :caseId')
            ->setParameter('caseId', $caseId);

        if ($objId) {
            $queryBuilder->orWhere('m.id = :id')->setParameter('id', $objId);
        }

        try {
            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        } catch (NoResultException $exception) {
            $cls = $this->getClassName();
            $res = new $cls();
            $res->setCaseId($caseId);

            return $res;
        }
    }

    /**
     * @param array $params
     * @return mixed|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findWithRelations(array $params)
    {
        $qb = $this->createQueryBuilder('c')
            ->addSelect('sl,rl,nl,s')
            ->leftJoin('c.site','s')
            ->leftJoin('c.siteLab', 'sl')
            ->leftJoin('c.referenceLab', 'rl')
            ->leftJoin('c.nationalLab', 'nl');

        foreach ($params as $field => $value) {
            $param = sprintf("%sField", $field);
            $qb->andWhere(sprintf('c.%s = :%s', $field, $param))->setParameter($param, $value);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $requiredField
     * @param array $criteria
     * @param string|null $class
     */
    private function checkRequiredField($requiredField, array $criteria, $class = null)
    {
        if (!isset($criteria[$requiredField])) {
            throw new \InvalidArgumentException(sprintf('Missing required "%s" parameter key', $requiredField));
        }

        if($class !== null && !$criteria[$requiredField] instanceof $class) {
            throw new \InvalidArgumentException(sprintf('Unexpected type! Expecting \'%s\' to be class \'%s\' got \'%s\' instead.',$requiredField,$class,get_class($criteria[$requiredField])));
        }
    }

    /**
     * @param array $params
     * @return mixed|null
     */
    public function findBySiteAndCaseId(array $params)
    {
        $this->checkRequiredField('site',$params,'NS\SentinelBundle\Entity\Site');
        $this->checkRequiredField('caseId',$params);

        $cases = $this->findWithRelations(array('caseId'=> $params['caseId']));

        if (empty($cases)) {
            return null;
        }

        if (count($cases) > 1) {
            throw new DuplicateCaseException(array('found' => count($cases), 'caseId' => $params['caseId']));
        }

        $case = current($cases);

        if (!$case->isUnlinked() && $case->getSite() && $case->getSite()->getCode() !== $params['site']->getCode()) {
            throw new InvalidCaseException(sprintf("Retrieved a single case '%s' with an existing site mis-match. caseSite: %s vs requestedSite: %s",$params['caseId'],$case->getSite(),$params['site']->getCode()));
        }

        return $case;
    }

    /**
     * @param array $params
     * @return mixed|null
     */
    public function findByCaseIdAndCheckCountry(array $params)
    {
        $this->checkRequiredField('country', $params, 'NS\SentinelBundle\Entity\Country');
        $this->checkRequiredField('caseId', $params);

        $ret = $this->findWithRelations(array('caseId' => $params['caseId']));

        if (empty($ret)) {
            return null;
        } elseif (count($ret) == 1) {
            return current($ret);
        }

        $found = 0;

        foreach ($ret as $case) {
            if ($case->getCountry()->getCode() == $params['country']->getCode()) {
                $found++;
                $caseRet = $case;

                if ($found > 1) {
                    throw new DuplicateCaseException(array('found' => $found, 'caseId' => $params['caseId'], 'country' => $params['country']));
                }
            }
        }

        return $caseRet;
    }
}
