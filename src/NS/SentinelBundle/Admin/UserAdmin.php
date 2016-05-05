<?php

namespace NS\SentinelBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use \NS\SentinelBundle\Form\Types\Role;

class UserAdmin extends Admin
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param EncoderFactoryInterface $factory
     */
    public function setEncoderFactory(EncoderFactoryInterface $factory)
    {
        $this->encoderFactory = $factory;
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage($tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('email')
            ->add('admin')
            ->add('canCreateCases')
            ->add('canCreateLabs')
            ->add('canCreateRRLLabs')
            ->add('canCreateNLLabs');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('email')
            ->add('admin')
            ->add('canCreateCases')
            ->add('canCreateLabs')
            ->add('canCreateRRLLabs')
            ->add('canCreateNLLabs')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('email')
            ->add('plainPassword', 'repeated',
                array(
                    'type' => 'password',
                    'invalid_message' => 'The password fields must match.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => false,
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                )
            )
            ->add('active', null, array('required' => false))
            ->add('admin', null, array('required' => false))
            ->add('canCreateCases', null, array('required' => false, 'label' => 'admin.form-can-create-case-record'))
            ->add('canCreateLabs', null, array('required' => false, 'label' => 'admin.form-can-create-sitelab-record'))
            ->add('canCreateRRLLabs', null, array('required' => false, 'label' => 'admin.form-can-create-reference-lab-record'))
            ->add('canCreateNLLabs', null, array('required' => false, 'label' => 'admin.form-can-create-national-lab-record'))
            ->add('referenceLab', null, array('required' => false, 'label' => 'admin.form-reference-lab'))
            ->add('acls', 'sonata_type_collection', array('by_reference' => true), array('edit' => 'inline', 'inline' => 'table'));
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('email')
            ->add('admin')
            ->add('canCreateCases')
            ->add('canCreateLabs');
    }

    /**
     * @param mixed $user
     * @return mixed
     */
    public function prePersist($user)
    {
        $encoder = $this->encoderFactory->getEncoder($user);

        $user->resetSalt();
        $user->setPassword($encoder->encodePassword($user->getPlainPassword(), $user->getSalt()));

        if ($user->getAcls()) {
            foreach ($user->getAcls() as $a) {
                $a->setUser($user);
            }
        }

        return $user;
    }

    /**
     * @param mixed $user
     * @return mixed
     */
    public function preUpdate($user)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        $plainPw = $user->getPlainPassword();

        if (strlen($plainPw) > 0) {
            $user->resetSalt();
            $user->setPassword($encoder->encodePassword($plainPw, $user->getSalt()));
        }

        foreach ($user->getAcls() as $a) {
            $a->setUser($user);
        }

        return $user;
    }

    /**
     * @param string $context
     * @return \Sonata\AdminBundle\Datagrid\ProxyQueryInterface
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $user = $this->tokenStorage->getToken()->getUser();
        $role = new Role();

        if ($user->isOnlyAdmin()) {
            $rootAlias = $query->getRootAlias();
            $query->leftJoin("$rootAlias.acls", 'a');

            return $query;
        }

        $highest = $role->getHighest($this->tokenStorage->getToken()->getRoles());

        if ($highest === null) {
            throw new \RuntimeException("Unable to determine highest role");
        }

        $rootAlias = $query->getRootAlias();
        $query->leftJoin("$rootAlias.acls", 'a')
            ->where('a.type >= :type')
            ->setParameter('type', $highest);

        return $query;
    }
}
