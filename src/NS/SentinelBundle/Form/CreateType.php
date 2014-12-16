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
    private $entityMgr;

    /**
     * @param SerializedSitesInterface $siteSerializer
     * @param ObjectManager $entityMgr
     */
    public function __construct(SerializedSitesInterface $siteSerializer, ObjectManager $entityMgr)
    {
        $this->siteSerializer = $siteSerializer;
        $this->entityMgr      = $entityMgr;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('caseId', null,   array('label'=>'site-assigned-case-id'))
            ->add('type', 'CreateRoles', array('description' => 'This should always be "1"'))
        ;

        if($this->siteSerializer->hasMultipleSites())
        {
            $queryBuilder = $this->entityMgr->getRepository('NS\SentinelBundle\Entity\Site')->getChainQueryBuilder()->orderBy('s.name', 'ASC');
            $builder->add('site','entity',array('required'        => true,
                                                'mapped'          => false,
                                                'empty_value'     => 'Please Select...',
                                                'label'           => 'ibd-form.site',
                                                'query_builder'   => $queryBuilder,
                                                'class'           => 'NS\SentinelBundle\Entity\Site',
                                                'auto_initialize' => false));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'create_case';
    }
}
