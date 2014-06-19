<?php

namespace NS\ApiBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use NS\ApiBundle\Service\OAuth2Client;
use Sonata\AdminBundle\Route\RouteCollection;

class RemoteAdmin extends Admin
{
    protected $oauth2;

    public function setOAuth2Client(OAuth2Client $client)
    {
        $this->oauth2 = $client;
    }

    public function generateObjectUrl($name, $object, array $parameters = array(), $absolute = false)
    {
        if($name == 'authorize')
        {
            $this->oauth2->setRemote($object);
            return $this->oauth2->getAuthenticationUrl();
        }
        else
            return parent::generateObjectUrl ($name, $object, $parameters, $absolute);

        return $this->generateUrl($name, $parameters, $absolute);
    }

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
//            ->add('clientId')
//            ->add('clientSecret')
            ->add('tokenEndpoint')
            ->add('authEndpoint')
//            ->add('redirectUrl')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                    'authorize'=> array('template'=>'NSApiBundle:RemoteAdmin:list__action_authorize.html.twig')
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
            ->add('user',null,array('empty_value'=>'Please Select', 'query_builder'=>function(\Doctrine\ORM\EntityRepository $repo){
                                                return $repo->createQueryBuilder('u')
                                                            ->leftJoin('u.acls','a')
                                                            ->addSelect('a')
                                                            ->where('a.type = :apiType')
                                                            ->setParameter('apiType',\NS\SentinelBundle\Form\Types\Role::API);
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
