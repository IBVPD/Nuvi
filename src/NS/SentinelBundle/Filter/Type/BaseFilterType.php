<?php

namespace NS\SentinelBundle\Filter\Type;

use \Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use NS\SecurityBundle\Role\ACLConverter;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class BaseFilterType
 * @package NS\SentinelBundle\Filter\Type
 */
class BaseFilterType extends AbstractType
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var ACLConverter
     */
    private $aclConverter;

    /**
     * BaseFilterType constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authChecker
     * @param ACLConverter $aclConverter
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker, ACLConverter $aclConverter)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker  = $authChecker;
        $this->aclConverter = $aclConverter;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('case_id', 'Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType', array(
                'required'          => false,
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
                'label'             => 'site-assigned-case-id'
            ))
            ->add('adm_date', 'Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType', array(
                'required' => false,
                'label'    => 'filter.admission-date'
            ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'preSetData'));
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form  = $event->getForm();
        $token = $this->tokenStorage->getToken();

        if ($this->authChecker->isGranted('ROLE_REGION')) {
            $objectIds = $this->aclConverter->getObjectIdsForRole($token, 'ROLE_REGION');
            if (count($objectIds) > 1) {
                $form->add('region', 'NS\SentinelBundle\Filter\Type\RegionType');
            }

            $form
                ->add('id', 'Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType', array(
                    'required'          => false,
                    'condition_pattern' => FilterOperands::STRING_CONTAINS,
                    'label'             => 'db-generated-id')
                )
                ->add('country', 'NS\SentinelBundle\Filter\Type\CountryType', array('required'=>false, 'placeholder'=>''))
                ->add('site', 'NS\SentinelBundle\Filter\Type\SiteType');
        }

        if ($this->authChecker->isGranted('ROLE_COUNTRY')) {
            $objectIds = $this->aclConverter->getObjectIdsForRole($token, 'ROLE_COUNTRY');
            if (count($objectIds) > 1) {
                $form->add('country', 'NS\SentinelBundle\Filter\Type\CountryType', array('required'=>false, 'placeholder'=>''));
            }

            $form
                ->add('firstName','Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType',array('condition_pattern'=>FilterOperands::STRING_CONTAINS))
                ->add('lastName','Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType',array('condition_pattern'=>FilterOperands::STRING_CONTAINS))
                ->add('site', 'NS\SentinelBundle\Filter\Type\SiteType');
        }

        if ($this->authChecker->isGranted('ROLE_SITE')) {
            $objectIds = $this->aclConverter->getObjectIdsForRole($token, 'ROLE_SITE');
            if (count($objectIds) > 1) {
                $form->add('site', 'NS\SentinelBundle\Filter\Type\SiteType');
            }

            $form
                ->add('firstName','Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType',array('condition_pattern'=>FilterOperands::STRING_CONTAINS))
                ->add('lastName','Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType',array('condition_pattern'=>FilterOperands::STRING_CONTAINS));

        }

        $form->add('find', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array(
            'label'=> 'find',
            'type' => 'submit',
            'icon' => 'fa fa-search',
            'attr' => array('class' => 'btn btn-xs btn-success pull-right')));

        $form->add('reset', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array(
            'label'=>'reset',
            'type' => 'reset',
            'icon' => 'fa fa-times-circle',
            'attr' => array('class' => 'btn btn-xs btn-danger', 'type'=>'submit')));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'GET',
        ));
    }
}
