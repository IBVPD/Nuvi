<?php

namespace NS\SentinelBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ResultPerPage
 *
 * @author gnat
 */
class ResultPerPage extends AbstractType
{
    private $target;

    /**
     * @param string $target
     */
    public function __construct($target = null)
    {
        $this->target = $target;
    }

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $target = $this->target;
        $builder->add('recordsperpage','recordsperpage');
        $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(FormEvent $event) use ($target)
                {
                    $form = $event->getForm();
                    $form->add('target','hidden',array('data'=>$target));
                });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'results_per_page';
    }
}
