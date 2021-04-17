<?php declare(strict_types=1);

namespace NS\SentinelBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use NS\UtilBundle\Form\Types\ArrayChoice;

abstract class AbstractReportCommonRepository extends Common
{
    abstract public function getCountQueryBuilder(string $alias, array $siteCodes): QueryBuilder;

    public function getDischargeOutcomeCountBySites(string $alias, array $siteCodes, ?bool $groupByYear = null): QueryBuilder
    {
        if ($groupByYear === true) {
            return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount, YEAR(%s.adm_date) as caseYear, s.code', $alias, $alias, $alias))
                ->andWhere(sprintf('(%s.disch_outcome IS NOT NULL AND %s.disch_outcome NOT IN (:noSelection,:outOfRange))', $alias, $alias))
                ->setParameter('noSelection', ArrayChoice::NO_SELECTION)
                ->setParameter('outOfRange', ArrayChoice::OUT_OF_RANGE)
                ->addGroupBy('caseYear');
        }

        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('(%s.disch_outcome IS NOT NULL AND %s.disch_outcome NOT IN (:noSelection,:outOfRange))', $alias, $alias))
            ->setParameter('noSelection', ArrayChoice::NO_SELECTION)
            ->setParameter('outOfRange', ArrayChoice::OUT_OF_RANGE);
    }

    public function getDischargeClassificationCountBySites(string $alias, array $siteCodes, ?bool $groupByYear = null): QueryBuilder
    {
        if ($groupByYear === true) {
            return $this->getCountQueryBuilder($alias, $siteCodes)
                ->select(sprintf('%s.id,COUNT(%s.id) as caseCount, YEAR(%s.adm_date) as caseYear, s.code', $alias, $alias, $alias))
                ->andWhere(sprintf('(%s.disch_class IS NOT NULL AND %s.disch_class NOT IN (:noSelection,:outOfRange))', $alias, $alias))
                ->setParameter('noSelection', ArrayChoice::NO_SELECTION)
                ->setParameter('outOfRange', ArrayChoice::OUT_OF_RANGE)
                ->addGroupBy('caseYear');
        }

        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select(sprintf('%s.id,COUNT(%s.id) as caseCount,s.code', $alias, $alias))
            ->andWhere(sprintf('(%s.disch_class IS NOT NULL AND %s.disch_class NOT IN (:noSelection,:outOfRange))', $alias, $alias))
            ->setParameter('noSelection', ArrayChoice::NO_SELECTION)
            ->setParameter('outOfRange', ArrayChoice::OUT_OF_RANGE);
    }

    protected function _getByDischargeClassificationDosesAndAge(string $alias, array $siteCodes, string $doseAlias): QueryBuilder
    {
        return $this->getCountQueryBuilder($alias, $siteCodes)
            ->select("
            CASE 
                WHEN $alias.age_months < 0 THEN -1
                WHEN $alias.age_months <= 2 THEN 2 
                WHEN $alias.age_months <= 3 THEN 3 
                WHEN $alias.age_months <= 11 THEN 11 
                WHEN $alias.age_months <= 23 THEN 23 
                WHEN $alias.age_months <= 59 THEN 59 
            ELSE -1
            END as age,
            $alias.id, COUNT($alias.id) as caseCount, $alias.disch_class, $alias.ageDistribution, $alias.$doseAlias, s.code")
            ->andWhere("$alias.$doseAlias IS NOT NULL AND $alias.$doseAlias > 0 AND $alias.disch_class >= 0")
            ->groupBy("$alias.disch_class, age, $alias.$doseAlias");
    }
}
