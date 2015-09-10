<?php

namespace NS\SentinelBundle\Form\Filters;

use NS\SentinelBundle\Form\Types\CaseStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\EmbeddedFilterTypeInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;

/**
 * Description of LabFilterType
 *
 * @author gnat
 */
class LabFilterType extends AbstractType implements EmbeddedFilterTypeInterface
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('status', 'CaseStatus',array( 'required'=>false, 'label' => 'filter-case-lab-status', 'apply_filter' => array($this,'labFilter')));
    }

    /**
     * @param QueryInterface $filterQuery
     * @param array $field
     * @param \NS\SentinelBundle\Form\Filters\CaseStatus $values
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function labFilter(QueryInterface $filterQuery, $field, $values)
    {
        if ($values['value'] instanceof CaseStatus && $values['value']->getValue() >= 0)
        {
            $queryBuilder = $filterQuery->getQueryBuilder();

            $queryBuilder->andWhere($filterQuery->getExpr()->eq('l.status', $values['value']->getValue()));
        }
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'lab_filter';
    }
}
