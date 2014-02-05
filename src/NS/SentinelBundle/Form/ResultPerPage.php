<?php

namespace NS\SentinelBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\Form\FormEvent;

/**
 * Description of ResultPerPage
 *
 * @author gnat
 */
class ResultPerPage extends AbstractType
{
    private $target;

    public function __construct($target = null)
    {
        $this->target = $target;
    }

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

    public function getName()
    {
        return 'results_per_page';
    }
}
