<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RotaVirusSiteLabType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stoolReceivedDate')
            ->add('stoolAdequate')
            ->add('stoolELISADone')
            ->add('stoolTestDate')
            ->add('stoolELISAResult')
            ->add('stoolStored')
            ->add('stoolSentToRRL')
            ->add('stoolSentToRRLDate')
            ->add('rrlELISAResult')
            ->add('rrlGenoTypeDate')
            ->add('rrlGenoTypeResult')
            ->add('stoolSentToNL')
            ->add('stoolSentToNLDate')
            ->add('nlELISAResult')
            ->add('nlGenoTypeDate')
            ->add('nlGenoTypeResult')
            ->add('case')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\RotaVirusSiteLab'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ns_sentinelbundle_rotavirussitelab';
    }
}
