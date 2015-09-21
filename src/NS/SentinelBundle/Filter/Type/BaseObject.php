<?php

namespace NS\SentinelBundle\Filter\Type;

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
    /**
     * @var
     */
    protected $entityMgr;

    /**
     * @var
     */
    protected $class;

    /**
     * @param ObjectManager $entityMgr
     * @param string $class
     */
    public function __construct(ObjectManager $entityMgr, $class = null)
    {
        $this->entityMgr = $entityMgr;
        $this->class     = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $class = $this->class;
        $resolver->setDefaults(array
                                (
                                'class'         => $this->class,
                                'multiple'      => true,
                                'query_builder' => $this->entityMgr->getRepository($this->class)->getAllSecuredQueryBuilder(),
                                'apply_filter'  => function (ORMQuery $filterBuilder, $field, $values) use ($class)
                                    {
                                        if (!empty($values['value']))
                                        {
                                            $fieldName    = str_replace(array('\\',':'),array('_','_'),$class);
                                            $values       = $values['value'];
                                            $queryBuilder = $filterBuilder->getQueryBuilder();

                                            if(count($values) == 1) {
                                                $queryBuilder->andWhere(sprintf("%s = :%s", $field, $fieldName))->setParameter($fieldName, $values[0]);
                                            } elseif (count($values) > 0) {
                                                $where  = array();
                                                
                                                foreach($values as $x => $val) {
                                                    $fieldNamex = $fieldName.$x;
                                                    $where[] = $field.'= :'.$fieldNamex;
                                                    $queryBuilder->setParameter($fieldNamex,$val);
                                                }

                                                $queryBuilder->andWhere("(".implode(" OR ",$where).")");
                                            }
                                        }
                                    }
                                )
                              );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'filter_entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_object';
    }
}
