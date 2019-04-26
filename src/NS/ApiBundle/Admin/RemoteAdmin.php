<?php

namespace NS\ApiBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class RemoteAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('clientId')
            ->add('clientSecret')
            ->add('tokenEndpoint')
            ->add('authEndpoint')
            ->add('redirectUrl')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('user')
            ->add('tokenEndpoint')
            ->add('authEndpoint')
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
            ->add('clientId')
            ->add('clientSecret')
            ->add('tokenEndpoint')
            ->add('authEndpoint')
            ->add('redirectUrl')
            ->add('user', null, [
                'placeholder' => 'Please Select',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('u')
                        ->leftJoin('u.acls', 'a')
                        ->addSelect('a')
                        ->where('a.options LIKE :apiType')
                        ->setParameter('apiType', '%api%');
            }])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('clientId')
            ->add('clientSecret')
            ->add('tokenEndpoint')
            ->add('authEndpoint')
            ->add('redirectUrl')
        ;
    }
}
