<?php

namespace NS\ImportBundle\Form;

use \Doctrine\Common\Persistence\ObjectManager;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolver;

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
                'placeholder'   => 'Please Select...',
                'query_builder' => $this->entityMgr->getRepository('NSImportBundle:Map')->getWithColumnsQuery(),
                )
            )
            ->add('sourceFile', 'vich_file',array('error_bubbling'=>false))
            ->add('import', 'submit', array('attr' => array('class' => 'btn btn-xs btn-success pull-right')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');
        $resolver->setDefaults(array(
            'data_class' => 'NS\ImportBundle\Entity\Import',
            'empty_data' => function(\Symfony\Component\OptionsResolver\Options $options) {
                return function() use ($options) {
                    return new \NS\ImportBundle\Entity\Import($options['user']);
                };
            }
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