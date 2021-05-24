<?php

namespace NS\SentinelBundle\Filter\Type;

use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EmbeddedFilterTypeInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use NS\AceBundle\Form\AutocompleterType;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Form\IBD\Types\IntenseSupport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteFilterType extends AbstractType implements EmbeddedFilterTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', AutocompleterType::class, [
            'required' => false,
            'class'    => Site::class,
            'multiple' => true,
            'route'    => 'siteSearch',
            'label'    => $options['label'] ?? null,
            'apply_filter' => [$this,'autoCompleteFilter'],
        ]);

        if ($options['include_intense']) {
            $builder->add('ibdIntenseSupport', IntenseSupport::class, ['required' => false, 'apply_filter' => [$this, 'applyFilter']]);
        }
    }

    public function autoCompleteFilter(QueryInterface $filterBuilder, $fields, $values): void
    {
        if (!empty($values['value'])) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $filterBuilder->getQueryBuilder();

            $queryBuilder->andWhere("{$values['alias']} IN (:sites)")->setParameter('sites', $values['value']);
        }
    }

    public function applyFilter(QueryInterface $filterBuilder, $field, $values): void
    {
        if ($values['value'] instanceof IntenseSupport && $values['value']->getValue() >= 0) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $filterBuilder->getQueryBuilder();
            $joins        = $queryBuilder->getDQLPart('join');
            $alias        = $values['alias'];

            if (empty($joins)) {
                $queryBuilder->innerJoin(current($queryBuilder->getRootAliases()) . '.site', 's');
                $alias = 's';
            } else {
                foreach (current($joins) as $join) {
                    if ($join->getJoin() === $alias) {
                        $alias = $join->getAlias();
                    }
                }
            }

            $queryBuilder->andWhere($filterBuilder->getExpr()->eq($alias . '.ibdIntenseSupport', $values['value']->getValue()));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('include_intense', true);
    }
}
