<?php

namespace NS\SentinelBundle\Filter\Type;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EmbeddedFilterTypeInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use NS\SentinelBundle\Form\Types\CaseStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LabFilterType extends AbstractType implements EmbeddedFilterTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('status', CaseStatus::class, ['required' => false, 'label' => 'filter-case-lab-status', 'apply_filter' => [$this, 'labFilter']]);
    }

    public function labFilter(QueryInterface $filterQuery, $field, $values): void
    {
        if ($values['value'] instanceof CaseStatus && $values['value']->getValue() >= 0) {
            $queryBuilder = $filterQuery->getQueryBuilder();

            $queryBuilder->andWhere($filterQuery->getExpr()->eq('l.status', $values['value']->getValue()));
        }
    }
}
