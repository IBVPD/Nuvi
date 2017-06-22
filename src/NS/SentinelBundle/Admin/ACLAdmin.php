<?php

namespace NS\SentinelBundle\Admin;

use NS\SecurityBundle\Form\Types\ACLAutoCompleterType;
use NS\SentinelBundle\Form\Types\Role;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ACLAdmin extends AbstractAdmin
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
            ->add('type', Role::class, ['label' => 'Access Level'])
            ->add('options', ChoiceType::class, [
                'label' => 'Creation Rights',
                'required' => false,
                'multiple' => true,
                'choices' => [
                    'Api Access' => 'api',
                    'Import Access' => 'import',
                    'Can Create Case' => 'case',
                    'Can Create Site Lab' => 'lab',
                    'Can Create NL' => 'nl',
                    'Can Create RRL' => 'rrl']]
            )
            ->add('object_id', ACLAutoCompleterType::class, [
                                                        'label'               => 'Specific - Site / Country / Region',
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
