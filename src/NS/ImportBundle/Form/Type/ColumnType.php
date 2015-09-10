<?php

namespace NS\ImportBundle\Form\Type;

use \NS\ImportBundle\Converter\ColumnChooser;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ColumnType
 *
 * @author gnat
 */
abstract class ColumnType extends AbstractType
{
    private $chooser;
    private $type;

    /**
     * @param ColumnChooser $chooser
     * @param string $type
     */
    public function __construct(ColumnChooser $chooser, $type)
    {
        $this->chooser = $chooser;
        $this->type    = $type;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices'     => $this->chooser->getChoices($this->type),
            'placeholder' => 'Please Select',
            'attr'        => array('class' => 'chosen-select','data-search-contains'=>true)
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }
}
