<?php

namespace NS\ApiBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

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
            ->add('redirectUris',       'TextToArray')
            ->add('allowedGrantTypes',  'OAuthGrantTypes')
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
