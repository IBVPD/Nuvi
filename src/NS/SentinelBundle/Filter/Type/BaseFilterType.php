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
            ->add('caseId', 'filter_text', array(
                'required'          => false,
                'condition_pattern' => FilterOperands::STRING_BOTH,
                'label'             => 'site-assigned-case-id'
            ))
            ->add('admDate', 'filter_date_range', array(
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
                ->add('id', 'filter_text', array(
                    'required'          => false,
                    'condition_pattern' => FilterOperands::STRING_BOTH,
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

            $form->add('site', 'NS\SentinelBundle\Filter\Type\SiteType');
        }

        if ($this->authChecker->isGranted('ROLE_SITE')) {
            $objectIds = $this->aclConverter->getObjectIdsForRole($token, 'ROLE_SITE');
            if (count($objectIds) > 1) {
                $form->add('site', 'NS\SentinelBundle\Filter\Type\SiteType');
            }
        }

        $form->add('find', 'NS\AceBundle\Form\IconButtonType', array(
            'type' => 'submit',
            'icon' => 'fa fa-search',
            'attr' => array('class' => 'btn btn-sm btn-success')));
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

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'base_filter_form';
    }
}
