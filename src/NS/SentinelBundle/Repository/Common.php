<?php

namespace NS\SentinelBundle\Repository;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnexpectedResultException;
use NS\ImportBundle\Exceptions\DuplicateCaseException;
use NS\SecurityBundle\Doctrine\SecuredEntityRepository;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Exceptions\InvalidCaseException;
use NS\UtilBundle\Service\AjaxAutocompleteRepositoryInterface;

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
        return $this->getAllSecuredQueryBuilder()->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();
    }

    /**
     * @param string $alias
     * @return QueryBuilder
     */
    public function getAllSecuredQueryBuilder($alias = 'o')
    {
        return $this->secure($this->_em
            ->createQueryBuilder()
            ->select($alias)
            ->from($this->getClassName(), $alias, sprintf('%s.code', $alias))
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

        return $this->secure($queryBuilder)->getQuery();
    }

    /**
     * @param $caseId
     * @param Site $site
     * @param string|null $objId
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOrCreate($caseId, Site $site, $objId = null)
    {
        if ($objId === null && $caseId === null) {
            throw new \InvalidArgumentException("Id or Case must be provided");
        }

        $queryBuilder = $this->createQueryBuilder('m')
            ->select('m,s,c,r')
            ->innerJoin('m.site', 's')
            ->innerJoin('s.country', 'c')
            ->innerJoin('m.region', 'r')
            ->where('m.case_id = :caseId AND m.site = :site')
            ->setParameter('caseId', $caseId)
            ->setParameter('site', $site);

        if ($objId) {
            $queryBuilder->andWhere('m.id = :id')->setParameter('id', $objId);
        }

        try {
            return $this->secure($queryBuilder)->getQuery()->getSingleResult();
        } catch (NoResultException $exception) {
            $className = $this->getClassName();
            $res = new $className();
            $res->setCaseId($caseId);

            return $res;
        }
    }

    public function findWithAssociations($caseId)
    {
        try {
            return $this->createQueryBuilder('c')
                ->addSelect('l,nl,rl')
                ->leftJoin('c.siteLab', 'l')
                ->leftJoin('c.nationalLab', 'nl')
                ->leftJoin('c.referenceLab', 'rl')
                ->where('c.id = :caseId')
                ->setParameter('caseId', $caseId)
                ->getQuery()
                ->getSingleResult();
        } catch (UnexpectedResultException $exception) {
            return null;
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
            ->leftJoin('c.site', 's')
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

        if ($class !== null && !$criteria[$requiredField] instanceof $class) {
            throw new \InvalidArgumentException(sprintf('Unexpected type! Expecting \'%s\' to be class \'%s\' got \'%s\' instead.', $requiredField, $class, get_class($criteria[$requiredField])));
        }
    }

    /**
     * @param array $params
     * @return mixed|null
     */
    public function findBySiteAndCaseId(array $params)
    {
        $this->checkRequiredField('site', $params, 'NS\SentinelBundle\Entity\Site');
        $this->checkRequiredField('case_id', $params);

        $cases = $this->findWithRelations(['case_id'=> $params['case_id']]);

        if (empty($cases)) {
            return null;
        }

        if (count($cases) > 1) {
            throw new DuplicateCaseException(['found' => count($cases), 'case_id' => $params['case_id']], 1);
        }

        $case = current($cases);

        if (!$case->isUnlinked() && $case->getSite() && $case->getSite()->getCode() !== $params['site']->getCode()) {
            throw new InvalidCaseException(sprintf("Retrieved a single case '%s' with an existing site mis-match. caseSite: %s vs requestedSite: %s", $params['case_id'], $case->getSite(), $params['site']->getCode()));
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
        $this->checkRequiredField('case_id', $params);

        $ret = $this->findWithRelations(['case_id' => $params['case_id']]);

        if (empty($ret)) {
            return null;
        } elseif (count($ret) == 1) {
            return current($ret);
        }

        $found = 0;
        $caseRet = null;

        /** @var BaseCase $case */
        foreach ($ret as $case) {
            if ($case->getCountry()->getCode() == $params['country']->getCode()) {
                $found++;
                $caseRet = $case;

                if ($found > 1) {
                    throw new DuplicateCaseException(['found' => $found, 'case_id' => $params['case_id'], 'country' => $params['country']]);
                }
            }
        }

        return $caseRet;
    }

    public function getGenderDistribution($alias)
    {
        return $this->createQueryBuilder($alias)
            ->select(sprintf('%s.gender, COUNT(%s.id) as caseCount',$alias,$alias))
            ->groupBy(sprintf('%s.gender',$alias));
    }

    public function getAgeInMonthDistribution($alias)
    {
        return $this->createQueryBuilder($alias)
            ->select(sprintf('%s.age_months, COUNT(%s.id) as caseCount',$alias,$alias))
            ->groupBy(sprintf('%s.age_months',$alias));;
    }

    public function getLocationDistribution($alias)
    {
        return $this->createQueryBuilder($alias)
            ->select(sprintf('%s.state, %s.district, COUNT(%s.id) as caseCount',$alias,$alias,$alias))
            ->groupBy(sprintf('%s.state,%s.district',$alias,$alias));
    }

    public function getDischargeOutcomeDistribution($alias)
    {
        return $this->createQueryBuilder($alias)
            ->select(sprintf('%s.disch_outcome as outcome, COUNT(%s.id) as caseCount',$alias,$alias))
            ->groupBy(sprintf('%s.disch_outcome',$alias));
    }

    public function getMonthlyDistribution($alias)
    {
        $config = $this->_em->getConfiguration();
        $config->addCustomDatetimeFunction('MONTH','DoctrineExtensions\Query\Mysql\Month');
        return $this->createQueryBuilder($alias)
            ->select(sprintf('MONTH(%s.adm_date) as theMonth, COUNT(%s.id) as caseCount',$alias,$alias))
            ->groupBy('theMonth');
    }

    public function getFailedLink($alias, array $countryCodes)
    {
        $queryBuilder = $this->createQueryBuilder($alias)
            ->addSelect('sl,r,c,rl')
            ->innerJoin($alias.'.country', 'c')
            ->leftJoin($alias.'.region','r')
            ->leftJoin($alias.'.siteLab','sl')
            ->innerJoin($alias.'.referenceLab', 'rl')
            ->where($alias.'.site IS NULL');

        if (empty($countryCodes)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->andWhere("($alias.country IN (:countries) )")
            ->setParameter('countries',array_unique($countryCodes));
    }

    public function getCasesPerMonth(\DateTime $from, \DateTime $to)
    {
        $config = $this->_em->getConfiguration();
        $config->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');

        return $this->secure(
                $this->_em->createQueryBuilder()
                    ->select('partial c.{id,adm_date}, s, MONTH(c.adm_date) AS theMonth, YEAR(c.adm_date) as theYear, COUNT(c.id) AS caseCount')
                    ->from($this->getEntityName(), 'c')
                    ->innerJoin('c.site', 's')
                    ->where('c.adm_date BETWEEN :start AND :end')
                    ->setParameters(['start' => $from, 'end' => $to])
                    ->groupBy('s.code,theMonth,theYear')
            )
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult();
    }
}
