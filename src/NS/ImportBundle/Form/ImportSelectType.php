<?php

namespace NS\ImportBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of ImportSelectType
 *
 * @author gnat
 */
class ImportSelectType extends AbstractType
{
    private $entityMgr;

    public function __construct(ObjectManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('map','entity',array( 'class'         => 'NSImportBundle:Map',
                                            'empty_value'   => 'Please Select...',
                                            'query_builder' => $this->entityMgr->getRepository('NSImportBundle:Map')->createQueryBuilder('m')->addSelect('c')->leftJoin('m.columns','c')->orderBy('m.name','ASC')->addOrderBy('m.version','ASC')->addOrderBy('c.order')
                                          ))
                ->add('file','file')
                ->add('import','submit')
                ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>'NS\ImportBundle\Entity\Import'
        ));
    }

    public function getName()
    {
        return 'ImportSelect';
    }
}
