<?php

namespace NS\SentinelBundle\Admin;

use NS\SecurityBundle\Form\Types\ACLAutoCompleterType;
use NS\SentinelBundle\Form\Types\Role;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ACLAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('type', 'role')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('type')
            ->add('object_id')
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ]
            ])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('type', Role::class)
            ->add('options', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'choices' => [
                    'api' => 'Api Access',
                    'import' => 'Import/Export Access',
                    'case' => 'Can Create Case',
                    'lab' => 'Can Create Site Lab',
                    'nl' => 'Can Create NL',
                    'rrl' => 'Can Create RRL']]
            )
            ->add('object_id', ACLAutoCompleterType::class, [
                                                        'label'               => 'Target',
                                                        'route'               => 'adminACLAjaxAutocomplete',
                                                        'required'            => false,
                                                        'secondary-field'     => ['s' => 'object_id', 'r' => 'type']])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('user')
            ->add('object_id')
            ->add('options')
            ->add('type')
        ;
    }
}
