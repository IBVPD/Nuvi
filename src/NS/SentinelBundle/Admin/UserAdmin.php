<?php

namespace NS\SentinelBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use \Symfony\Component\Security\Core\SecurityContextInterface;

class UserAdmin extends Admin
{
//    protected $baseRouteName = 'Users';
//    protected $baseRoutePattern = 'Users';
    private $factory;

    public function setEncoderFactory(EncoderFactoryInterface $factory )
    {
        $this->factory = $factory;
    }

    private $securityContext;
    public function setSecurityContext(SecurityContextInterface $sc )
    {
        $this->securityContext = $sc;
    }

    public function getTemplate($name)
    {
        if($name == 'edit')
            return 'NSSentinelBundle:Admin:User/edit.html.twig';
        else
            return parent::getTemplate($name);
    }
    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('email')
            ->add('isAdmin')
            ->add('canCreateCases')
            ->add('canCreateLabs')
            ->add('canCreateRRLLabs')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('email')
            ->add('isAdmin')
            ->add('canCreateCases')
            ->add('canCreateLabs')
            ->add('canCreateRRLLabs')
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
                ->add('name')
                ->add('email')
                ->add('plainPassword','repeated',
                           array(
                               'type'            => 'password',
                               'invalid_message' => 'The password fields must match.',
                               'options'         => array('attr' => array('class' => 'password-field')),
                               'required'        => false,
                               'first_options'   => array('label' => 'Password'),
                               'second_options'  => array('label' => 'Repeat Password'),
                               )
                      )
                ->add('isActive',null,array('required'=>false))
                ->add('isAdmin',null,array('required'=>false))
                ->add('canCreateCases',null,array('required'=>false))
                ->add('canCreateLabs',null,array('required'=>false))
                ->add('canCreateRRLLabs',null,array('required'=>false))
                ->add('acls', 'sonata_type_collection', array('by_reference'=>true),array('edit'=>'inline','inline'=>'table'))
            ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('email')
        ;
    }

    public function prePersist($user)
    {
        $encoder = $this->factory->getEncoder($user);

        $user->resetSalt();
        $user->setPassword($encoder->encodePassword($user->getPlainPassword(),$user->getSalt()));

        if($user->getAcls())
        {
            foreach ($user->getAcls() as $a) {
                $a->setUser($user);
            }
        }
        
        return $user;
    }

    public function preUpdate($user)
    {
        $encoder        = $this->factory->getEncoder($user);
        $plain_password = $user->getPlainPassword();

        if(strlen($plain_password)> 0)
        {
            $user->resetSalt();
            $user->setPassword($encoder->encodePassword($plain_password,$user->getSalt()));
        }

        foreach ($user->getAcls() as $a) {
            $a->setUser($user);
        }
        
        return $user;
    }

    public function createQuery($context = 'list')
    {
        $query   = parent::createQuery($context);
        $role    = new \NS\SentinelBundle\Form\Types\Role();
        $highest = $role->getHighest($this->securityContext->getToken()->getRoles());

        $ralias = $query->getRootAlias();
        $query->innerJoin("$ralias.acls",'a')
              ->where('a.type >= :type')
              ->setParameter('type',$highest);

        return $query;
    }
}
