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
    /* @var $entityMgr ObjectManager */
    private $entityMgr;

    /**
     * @param ObjectManager $entityMgr
     */
    public function __construct(ObjectManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('map', 'entity', array(
                'class'         => 'NSImportBundle:Map',
                'empty_value'   => 'Please Select...',
                'query_builder' => $this->entityMgr->getRepository('NSImportBundle:Map')->getWithColumnsQuery(),
                )
            )
            ->add('importFile', 'file')
            ->add('import', 'submit', array('attr' => array('class' => 'btn btn-xs btn-success pull-right')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\ImportBundle\Entity\Result'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ImportSelect';
    }
}