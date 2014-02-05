<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Security\Core\SecurityContext;

class MeningitisFilter extends AbstractType
{
    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id','filter_text',array('required'=>false,'condition_pattern'=>  \Lexik\Bundle\FormFilterBundle\Filter\FilterOperands::STRING_STARTS))
                ->add('site',null,array('required'=>false))
                ->add('country',null,array('required'=>false))
                ->add('region',null,array('required'=>false))
                ->add('find','submit',array('attr'=>array('class'=>'filter','label'=>'Find')));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\MeningitisFilter'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'meningitis_filter_form';
    }
}
