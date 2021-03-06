<?php

namespace NS\SentinelBundle\Filter\Type;

use Doctrine\ORM\QueryBuilder;
use Exception;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EmbeddedFilterTypeInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use NS\SentinelBundle\Form\IBD\Types\IntenseSupport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteFilterType extends AbstractType implements EmbeddedFilterTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $label = $options['label'] ?? null;

        $builder->add('name', TextFilterType::class, ['required' => false, 'label' => $label, 'apply_filter' => [$this,'applyFilter']]);

        if ($options['include_intense']) {
            $builder->add('ibdIntenseSupport', IntenseSupport::class, ['required' => false, 'apply_filter' => [$this, 'applyFilter']]);
        }
    }

    public function applyFilter(QueryInterface $filterBuilder, $field, $values): void
    {
        if ($values['value'] instanceof IntenseSupport && $values['value']->getValue() >= 0) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $filterBuilder->getQueryBuilder();
            $joins = $queryBuilder->getDQLPart('join');
            $alias = $values['alias'];

            if (empty($joins)) {
                $queryBuilder->innerJoin(current($queryBuilder->getRootAliases()).'.site','s');
                $alias = 's';
            } else {
                foreach (current($joins) as $join) {
                    if ($join->getJoin() == $alias) {
                        $alias = $join->getAlias();
                    }
                }
            }

            $queryBuilder->andWhere($filterBuilder->getExpr()->eq($alias . '.ibdIntenseSupport', $values['value']->getValue()));
        }

        if (!empty($values['value']) && is_string($values['value'])) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $filterBuilder->getQueryBuilder();
            $joins = $queryBuilder->getDQLPart('join');
            $alias = $values['alias'];

            if (empty($joins)) {
                $queryBuilder->innerJoin(current($queryBuilder->getRootAliases()).'.site','s');
                $alias = 's';
            } else {
                foreach (current($joins) as $join) {
                    if ($join->getJoin() == $alias) {
                        $alias = $join->getAlias();
                    }
                }
            }

            $queryBuilder->andWhere("$alias.name LIKE '%".$values['value']."%'");

        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('include_intense', true);
    }
}
