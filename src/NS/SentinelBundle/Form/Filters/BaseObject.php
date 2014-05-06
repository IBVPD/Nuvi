<?php

namespace NS\SentinelBundle\Form\Filters;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\EmbeddedFilterTypeInterface;
//use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;

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
        parent::setDefaultOptions($resolver);

        $qb = $this->em->getRepository($this->class)->getAllSecuredQueryBuilder();
        $resolver->setDefaults(array
                                (
//                                'data_extraction_method' => 'default',
                                'class'         => $this->class,
                                'multiple'      => true,
                                'query_builder' => $qb,
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
