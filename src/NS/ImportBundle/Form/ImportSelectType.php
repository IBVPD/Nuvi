<?php

namespace NS\ImportBundle\Form;

use \Doctrine\Common\Persistence\ObjectManager;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \NS\ImportBundle\Entity\Import;
use \Symfony\Component\HttpFoundation\File\File;
use \Vich\UploaderBundle\Mapping\PropertyMappingFactory;

/**
 * Description of ImportSelectType
 *
 * @author gnat
 */
class ImportSelectType extends AbstractType
{
    /* @var $entityMgr ObjectManager */
    private $entityMgr;

    private $factory;

    /**
     * @param ObjectManager $entityMgr
     */
    public function __construct(ObjectManager $entityMgr, PropertyMappingFactory $factory)
    {
        $this->entityMgr = $entityMgr;
        $this->factory = $factory;
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

        $builder->addEventListener(FormEvents::POST_SUBMIT,array($this,'postSubmit'));
    }

    /**
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        $import = $event->getData();
        $import->setDuplicateFile($this->createNewFile($import, 'duplicate-state.txt', 'duplicateFile'));
        $import->setMessageFile($this->createNewFile($import, 'messages.csv', 'messageFile'));
        $import->setWarningFile($this->createNewFile($import, 'warnings.csv', 'warningFile'));
        $import->setErrorFile($this->createNewFile($import, 'errors.csv', 'errorFile'));
        $import->setSuccessFile($this->createNewFile($import, 'successes.csv', 'successFile'));
    }

    /**
     * @param Import $import
     * @param $name
     * @param $property
     * @return File
     */
    public function createNewFile(Import $import, $name, $property)
    {
        $file = new File(tempnam(sys_get_temp_dir(), 'import-output'));
        $mapping = $this->factory->fromField($import, $property);
        if($mapping) {
            $mapping->setFileName($import, $name);

            // determine the file's directory
            $dir = $mapping->getUploadDir($import);

            $uploadDir = $mapping->getUploadDestination() . DIRECTORY_SEPARATOR . $dir;

            return $file->move($uploadDir, $name);
        }

        return $file;
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