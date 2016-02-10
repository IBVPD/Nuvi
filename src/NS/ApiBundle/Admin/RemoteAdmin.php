<?php

namespace NS\ApiBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use NS\SentinelBundle\Form\Types\Role;

class RemoteAdmin extends Admin
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
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
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
            ->add('user',null,array('placeholder'=>'Please Select', 'query_builder'=>function(\Doctrine\ORM\EntityRepository $repo){
                                                return $repo->createQueryBuilder('u')
                                                            ->leftJoin('u.acls','a')
                                                            ->addSelect('a')
                                                            ->where('a.type IN (:apiType)')
                                                            ->setParameter('apiType',array(Role::REGION_API,Role::COUNTRY_API,Role::SITE_API));
            }))
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
