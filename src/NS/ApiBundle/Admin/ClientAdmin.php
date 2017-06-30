<?php

namespace NS\ApiBundle\Admin;

use Doctrine\ORM\EntityRepository;
use NS\AceBundle\Form\TagType;
use NS\ApiBundle\Form\Types\OAuthGrantTypes;
use Sonata\AdminBundle\Admin\AbstractAdmin;
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
class ClientAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('redirectUris',       TagType::class, ['arrayOutput'=>true])
            ->add('allowedGrantTypes',  OAuthGrantTypes::class)
            ->add('user', null, [
                'placeholder' => 'Please Select',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('u')
                        ->leftJoin('u.acls', 'a')
                        ->addSelect('a')
                        ->where('a.options LIKE "%api%"');
            }]);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('publicId', null, ['label'=>'Client Id'])
            ->add('secret', null, ['label'=>'Client Secret'])
            ->add('user')
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
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('publicId', null, ['label'=>'Client Id'])
            ->add('secret', null, ['label'=>'Client Secret'])
            ->add('redirectUris')
            ->add('allowedGrantTypes')
        ;
    }
}
