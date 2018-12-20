<?php

namespace NS\SentinelBundle\Filter\Type;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of BaseObject
 *
 * @author gnat
 */
class BaseObject extends AbstractType //implements EmbeddedFilterTypeInterface
{
    /** @var ObjectManager */
    protected $entityMgr;

    /** @var string|null */
    protected $class;

    /**
     * @param ObjectManager $entityMgr
     * @param string $class
     */
    public function __construct(ObjectManager $entityMgr, $class = null)
    {
        $this->entityMgr = $entityMgr;
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => $this->class,
            'multiple' => true,
            'query_builder' => $this->entityMgr->getRepository($this->class)->getAllSecuredQueryBuilder(),
            'apply_filter' => [$this, 'applyFilter'],
        ]);
    }

    public function applyFilter(ORMQuery $filterBuilder, $field, $values)
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

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityFilterType::class;
    }
}
