<?php

namespace NS\ImportBundle\Form;

use \Doctrine\Common\Persistence\ObjectManager;
use \NS\ImportBundle\Services\ImportFileCreator;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \NS\ImportBundle\Entity\Import;

/**
 * Description of ImportSelectType
 *
 * @author gnat
 */
class ImportSelectType extends AbstractType
{
    /**
     * @var $entityMgr ObjectManager
     */
    private $entityMgr;

    /**
     * @var ImportFileCreator
     */
    private $fileCreator;

    /**
     * @param ObjectManager $entityMgr
     */
    public function __construct(ObjectManager $entityMgr, ImportFileCreator $fileCreator)
    {
        $this->entityMgr = $entityMgr;
        $this->fileCreator = $fileCreator;
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
                'property' => 'selectName'
                )
            )
            ->add('sourceFile', 'vich_file',array('error_bubbling'=>false))
            ->add('inputDateStart','acedatepicker',array('label'=>'Import file date start'))
            ->add('inputDateEnd','acedatepicker',array('label'=>'Import file date end'))
            ->add('import', 'submit', array('attr' => array('class' => 'btn btn-xs btn-success pull-right')))
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT,array($this,'postSubmit'));
    }

    /**
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        $import = $event->getData();
        $import->setDuplicateFile($this->fileCreator->createNewFile($import, 'duplicate-state.txt', 'duplicateFile'));
        $import->setMessageFile($this->fileCreator->createNewFile($import, 'messages.csv', 'messageFile'));
        $import->setWarningFile($this->fileCreator->createNewFile($import, 'warnings.csv', 'warningFile'));
        $import->setErrorFile($this->fileCreator->createNewFile($import, 'errors.csv', 'errorFile'));
        $import->setSuccessFile($this->fileCreator->createNewFile($import, 'successes.csv', 'successFile'));
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
