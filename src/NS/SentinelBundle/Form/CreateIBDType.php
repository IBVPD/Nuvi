<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NS\SentinelBundle\Services\SerializedSites;

/**
 * Description of CreateIBDType
 *
 * @author gnat
 */
class CreateIBDType extends AbstractType
{
    private $siteSerializer;
    private $em;

    public function __construct(SerializedSites $siteSerializer, ObjectManager $em)
    {
        $this->siteSerializer = $siteSerializer;
        $this->em             = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id',     null,   array('label'=>'db-generated-id','required'=>false))
            ->add('caseId', null,   array('label'=>'site-assigned-case-id'))
            ->add('type',   'IBDCreateRoles')
            ;
    
        if($this->siteSerializer->hasMultipleSites())
        {
            $builder->add('site','entity',array('required'        => true,
                                                'mapped'          => false,
                                                'empty_value'     => 'Please Select...',
                                                'label'           => 'meningitis-form.site',
                                                'query_builder'   => $this->em->getRepository('NS\SentinelBundle\Entity\Site')->getChainQueryBuilder(),
                                                'class'           => 'NS\SentinelBundle\Entity\Site',
                                                'auto_initialize' => false));
        }
    }

    public function getName()
    {
        return 'create_ibd';
    }
}
