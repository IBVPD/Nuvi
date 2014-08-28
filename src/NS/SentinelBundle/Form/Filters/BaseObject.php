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
                                            $qb        = $filterBuilder->getQueryBuilder();

                                            if(count($values) == 1)
                                                $qb->andWhere(sprintf("%s = :%s",$field,$fieldName))->setParameter($fieldName,$values[0]);
                                            else if (count($values) > 0)
                                            {
                                                $where  = array();
                                                
                                                foreach($values as $x => $val)
                                                {
                                                    $fieldNamex = $fieldName.$x;
                                                    $where[] = $field.'= :'.$fieldNamex;
                                                    $qb->setParameter($fieldNamex,$val);
                                                }

                                                $qb->andWhere("(".implode(" OR ",$where).")");
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
