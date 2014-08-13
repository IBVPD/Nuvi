<?php

namespace NS\SentinelBundle\Form\Filters;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery;

/**
 * Description of BaseObject
 *
 * @author gnat
 */
class BaseObject extends AbstractType //implements EmbeddedFilterTypeInterface
{
    protected $em;

    protected $class;

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $class = $this->class;
        $resolver->setDefaults(array
                                (
                                'class'         => $this->class,
                                'multiple'      => true,
                                'query_builder' => $this->em->getRepository($this->class)->getAllSecuredQueryBuilder(),
                                'apply_filter'  => function (ORMQuery $filterBuilder, $field, $values) use ($class)
                                    {
                                        if (!empty($values['value']))
                                        {
                                            $fieldName = str_replace(array('\\',':'),array('_','_'),$class);
                                            $values    = $values['value'];

                                            if(count($values) == 1)
                                            {
                                                $filterBuilder->getQueryBuilder()->andWhere($field.'= :'.$fieldName)->setParameter($fieldName,$values[0]);
                                            }
                                            else if (count($values) > 0)
                                            {
                                                $where  = array();
                                                $params = array();
                                                $qb = $filterBuilder->getQueryBuilder();
                                                foreach($values as $x => $val)
                                                {
                                                    $fieldNamex = $fieldName.$x;
                                                    $where[] = $field.'= :'.$fieldNamex;
                                                    $qb->setParameter($fieldNamex,$val);
                                                }

                                                $filterBuilder->getQueryBuilder()->andWhere("(".  implode(" OR ",$where).")");
                                            }
                                        }
                                    }
                                )
                              );
    }

    public function getParent()
    {
        return 'filter_entity';
    }

    public function getName()
    {
        return 'filter_object';
    }
}
