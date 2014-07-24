<?php

namespace NS\SentinelBundle\Form\Filters;

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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('status', 'CaseStatus',array( 'required'=>false, 'label' => 'filter-case-lab-status', 'apply_filter' => array($this,'labFilter')));
    }

    public function labFilter(QueryInterface $filterQuery, $field, $values)
    {
        if ($values['value'] instanceof CaseStatus && $values['value']->getValue() >= 0)
        {
            $qb = $filterQuery->getQueryBuilder();

            $qb->andWhere($filterQuery->getExpr()->eq('l.status', $values['value']->getValue()));
        }
    }

    public function getName()
    {
        return 'lab_filter';
    }
}
