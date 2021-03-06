<?php

namespace NS\SentinelBundle\Filter\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseObject extends AbstractType //implements EmbeddedFilterTypeInterface
{
    /** @var ObjectManager */
    protected $entityMgr;

    /** @var string|null */
    protected $class;

    public function __construct(ObjectManager $entityMgr, $class = null)
    {
        $this->entityMgr = $entityMgr;
        $this->class = $class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => $this->class,
            'multiple' => true,
            'query_builder' => $this->entityMgr->getRepository($this->class)->getAllSecuredQueryBuilder(),
            'apply_filter' => [$this, 'applyFilter'],
        ]);
    }

    public function applyFilter(ORMQuery $filterBuilder, $field, $values): void
    {
        if (!empty($values['value'])) {
            $fieldName = str_replace(['\\', ':'], '_', $this->class);
            $values = $values['value'];
            $queryBuilder = $filterBuilder->getQueryBuilder();

            if (count($values) === 1) {
                $queryBuilder->andWhere(sprintf('%s = :%s', $field, $fieldName))->setParameter($fieldName, $values[0]);
            } elseif (count($values) > 0) {
                $where = [];

                foreach ($values as $x => $val) {
                    $fieldNameX = $fieldName . $x;
                    $where[] = $field . '= :' . $fieldNameX;
                    $queryBuilder->setParameter($fieldNameX, $val);
                }

                $queryBuilder->andWhere('(' . implode(' OR ', $where) . ')');
            }
        }
    }

    public function getParent(): string
    {
        return EntityFilterType::class;
    }
}
