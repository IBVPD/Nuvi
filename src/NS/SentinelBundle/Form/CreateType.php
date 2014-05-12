<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NS\SentinelBundle\Interfaces\SerializedSitesInterface;

/**
 * Description of CreateIBDType
 *
 * @author gnat
 */
class CreateType extends AbstractType
{
    private $siteSerializer;
    private $em;
    private $type;

    public function __construct(SerializedSitesInterface $siteSerializer, ObjectManager $em, $type)
    {
        $this->siteSerializer = $siteSerializer;
        $this->em             = $em;
        $this->type           = $type;
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
            ->add('type',   $this->type)
            ;
    
        if($this->siteSerializer->hasMultipleSites())
        {
            $builder->add('site','entity',array('required'        => true,
                                                'mapped'          => false,
                                                'empty_value'     => 'Please Select...',
                                                'label'           => 'meningitis-form.site',
                                                'query_builder'   => $this->em->getRepository('NS\SentinelBundle\Entity\Site')->getChainQueryBuilder()->orderBy('s.name','ASC'),
                                                'class'           => 'NS\SentinelBundle\Entity\Site',
                                                'auto_initialize' => false));
        }
    }

    public function getName()
    {
        return $this->type == 'IBDCreateRoles' ? 'create_ibd':'create_rotavirus';
    }
}
