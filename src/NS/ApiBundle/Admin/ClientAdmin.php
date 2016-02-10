<?php

namespace NS\ApiBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use NS\SentinelBundle\Form\Types\Role;

/**
 * Description of ClientAdmin
 *
 * @author gnat
 */
class ClientAdmin extends Admin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('redirectUris',       'tag', array('arrayOutput'=>true))
            ->add('allowedGrantTypes',  'OAuthGrantTypes')
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
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('publicId',null,array('label'=>'Client Id'))
            ->add('secret',null,array('label'=>'Client Secret'))
            ->add('user')
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
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('publicId',null,array('label'=>'Client Id'))
            ->add('secret',null,array('label'=>'Client Secret'))
            ->add('redirectUris')
            ->add('allowedGrantTypes')
        ;
    }
}
