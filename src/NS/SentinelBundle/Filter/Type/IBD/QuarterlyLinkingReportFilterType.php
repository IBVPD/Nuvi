<?php

namespace NS\SentinelBundle\Filter\Type\IBD;

use NS\SentinelBundle\Filter\Type\BaseQuarterlyFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;

class QuarterlyLinkingReportFilterType extends BaseQuarterlyFilterType
{

    /**
     * @param QueryInterface $filterQuery
     * @param $field
     * @param $values
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function filterYear(QueryInterface $filterQuery, $field, $values)
    {
        if ($values['value'] > 0) {
            $queryBuilder = $filterQuery->getQueryBuilder();

            $config = $queryBuilder->getEntityManager()->getConfiguration();
            $config->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');

            $alias = $values['alias'];

            $queryBuilder
                ->andWhere(sprintf('YEAR(%s.sampleCollectionDate) = :%s_year',$alias, $alias))
                ->setParameter($alias.'_year',$values['value']);
        }
    }


    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'QuarterlyLinkingReportFilter';
    }

}
