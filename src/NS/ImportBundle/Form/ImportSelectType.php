<?php

namespace NS\ImportBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use NS\AceBundle\Form\DatePickerType;
use NS\ImportBundle\Services\ImportFileCreator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use NS\ImportBundle\Entity\Import;
use Vich\UploaderBundle\Form\Type\VichFileType;

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
     * @param ImportFileCreator $fileCreator
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
        $builder->add('map', EntityType::class, [
                'class'         => 'NSImportBundle:Map',
                'placeholder'   => 'Please Select...',
                'query_builder' => $this->entityMgr->getRepository('NSImportBundle:Map')->getWithColumnsQuery(),
                'property' => 'selectName',
            ]
            )
            ->add('referenceLab', EntityType::class, [
                'class' => 'NS\SentinelBundle\Entity\ReferenceLab',
                'placeholder' => 'Please Select...',
                'query_builder'=> function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('r')->orderBy('r.name', 'ASC');
                },
                'required' => false,
            ])
            ->add('sourceFile', VichFileType::class, ['error_bubbling'=>false])
            ->add('inputDateStart', DatePickerType::class, ['label'=>'Import file date start'])
            ->add('inputDateEnd', DatePickerType::class, ['label'=>'Import file date end'])
            ->add('import', SubmitType::class, ['attr' => ['class' => 'btn btn-xs btn-success pull-right','label'=>'Import']])
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'postSubmit']);
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
        $resolver->setDefaults([
            'data_class' => 'NS\ImportBundle\Entity\Import',
            'empty_data' => function (Options $options) {
                return function () use ($options) {
                    return new Import($options['user']);
                };
            }
        ]);
    }
}
