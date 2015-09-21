<?php

namespace NS\SentinelBundle\Filter\Type;

use \Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\EmbeddedFilterTypeInterface;
use \Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use \NS\SentinelBundle\Form\Types\IBDIntenseSupport;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of SiteFilterType
 *
 * @author gnat
 */
class SiteFilterType extends AbstractType implements EmbeddedFilterTypeInterface
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',               'filter_text',       array('required' => false))
                ->add('ibdIntenseSupport',  'IBDIntenseSupport', array('required' => false, 'apply_filter' => array($this,'applyFilter')));
    }

    /**
     * @param QueryInterface $filterBuilder
     * @param string $field
     * @param IBDIntenseSupport $values
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function applyFilter(QueryInterface $filterBuilder, $field, $values)
    {
        if ($values['value'] instanceof IBDIntenseSupport && $values['value']->getValue() >= 0)
        {
            $queryBuilder = $filterBuilder->getQueryBuilder();
            $joins        = $queryBuilder->getDQLPart('join');
            $alias        = $values['alias'];

            foreach(current($joins) as $join)
            {
                if($join->getJoin() == $alias)
                    $alias = $join->getAlias();
            }

            $queryBuilder->andWhere($filterBuilder->getExpr()->eq($alias.'.ibdIntenseSupport', $values['value']->getValue()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'SiteFilterType';
    }
}
