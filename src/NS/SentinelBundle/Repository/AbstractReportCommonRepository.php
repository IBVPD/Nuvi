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
}
