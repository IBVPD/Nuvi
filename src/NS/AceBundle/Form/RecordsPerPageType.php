<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\HttpFoundation\Session\Session;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\Form\FormEvent;

/**
 * Description of RecordsPerPageType
 *
 * @author gnat
 */
class RecordsPerPageType extends AbstractType
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array('5'=> 5,'10'=>10,'20'=>20,'30'=>30,'50'=>50,'75'=>75,'100'=>100),// I Can't seem to use range here as validation fails??
            'label'   => null,
            'data'    => $this->session->get('result_per_page',10),
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'recordsperpage';
    }
}
