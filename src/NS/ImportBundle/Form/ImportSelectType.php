<?php

namespace NS\ImportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of ImportSelectType
 *
 * @author gnat
 */
class ImportSelectType extends AbstractType
{
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('map','entity',array( 'class'         => 'NSImportBundle:Map',
                                            'empty_value'   => 'Please Select...',
                                            'query_builder' => $this->em->getRepository('NSImportBundle:Map')->createQueryBuilder('m')->addSelect('c')->leftJoin('m.columns','c')->orderBy('m.name','ASC')->addOrderBy('m.version','ASC')->addOrderBy('c.order')
                                          ))
                ->add('file','file')
                ->add('import','submit')
                ;
    }

    public function getName()
    {
        return 'ImportSelect';
    }
}
