<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SiteLabType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stoolReceivedDate',  'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolReceivedDate'))
            ->add('stoolAdequate',      'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolAdequate'))
            ->add('stoolELISADone',     'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolELISADone'))
            ->add('stoolTestDate',      'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolTestDate'))
            ->add('stoolELISAResult',   'ElisaResult',      array('required'=>false, 'label'=>'rotavirus-form.stoolELISAResult'))
            ->add('stoolStored',        'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolStored'))
            ->add('stoolSentToRRL',     'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolSentToRRL'))
            ->add('stoolSentToRRLDate', 'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolSentToRRLDate'))
            ->add('rrlELISAResult',     'ElisaResult',      array('required'=>false, 'label'=>'rotavirus-form.rrlELISAResult'))
            ->add('rrlGenoTypeDate',    'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.rrlGenoTypeDate'))
            ->add('rrlGenoTypeResult',  null,               array('required'=>false, 'label'=>'rotavirus-form.rrlGenoTypeResult'))
            ->add('stoolSentToNL',      'TripleChoice',     array('required'=>false, 'label'=>'rotavirus-form.stoolSentToNL'))
            ->add('stoolSentToNLDate',  'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.stoolSentToNLDate'))
            ->add('nlELISAResult',      'ElisaResult',      array('required'=>false, 'label'=>'rotavirus-form.nlELISAResult'))
            ->add('nlGenoTypeDate',     'acedatepicker',    array('required'=>false, 'label'=>'rotavirus-form.nlGenoTypeDate'))
            ->add('nlGenoTypeResult',   null,               array('required'=>false, 'label'=>'rotavirus-form.nlGenoTypeResult'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\Rota\SiteLab'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rotavirus_sitelab';
    }
}
