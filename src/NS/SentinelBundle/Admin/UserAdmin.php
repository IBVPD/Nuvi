<?php

namespace NS\SentinelBundle\Admin;

use NS\SentinelBundle\Entity\User;
use NS\SentinelBundle\Form\Types\Role;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use NS\SecurityBundle\Role\ACLConverter;

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
     * @var ACLConverter
     */
    private $aclConverter;

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
     * @param ACLConverter $aclConverter
     */
    public function setAclConverter($aclConverter)
    {
        $this->aclConverter = $aclConverter;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('email')
            ->add('active')
            ->add('admin');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('email')
            ->add('active')
            ->add('admin')
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
            ->add('plainPassword', RepeatedType::class,
                array(
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => false,
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                )
            )
            ->add('active', null, array('required' => false))
            ->add('admin', null, array('required' => false))
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
            ->add('active');
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
        $query->leftJoin(sprintf('%s.acls',$query->getRootAlias()),'a');

        $user = $this->tokenStorage->getToken()->getUser();

        // Super admins see all
        if ($user->isOnlyAdmin()) {
            return $query;
        }

        if(in_array('ROLE_SONATA_REGION_ADMIN',$user->getRoles())) {
            return $this->adjustRegionAccess($query);
        } elseif (in_array('ROLE_SONATA_COUNTRY_ADMIN', $user->getRoles())) {
            return $this->adjustCountryAccess($query);
        } else {
            throw new AccessDeniedException('Unable to secure user list');
        }
    }

    /**
     * @param ProxyQueryInterface $query
     * @return ProxyQueryInterface
     */
    private function adjustRegionAccess(ProxyQueryInterface $query)
    {
        $query->andWhere(' 
            (
                ( a.type = :regionType AND a.object_id IN (:regionIds) ) OR 
                ( a.type = :countryType AND a.object_id IN (SELECT c.code FROM NS\SentinelBundle\Entity\Country c WHERE c.region IN (:regions) ) ) OR
                ( a.type >= :siteType AND a.type <= :nlLabType AND a.object_id IN (SELECT s.code FROM NS\SentinelBundle\Entity\Site s INNER JOIN s.country ct WHERE ct.region IN (:regions) ) )
            )
          ')
            ->setParameter('regionType', Role::REGION)
            ->setParameter('regionIds', $this->getRegions())
            ->setParameter('regions', $this->getRegions())
            ->setParameter('countryType', Role::COUNTRY)
            ->setParameter('siteType', Role::SITE)
            ->setParameter('nlLabType', Role::NL_LAB);

        return $query;
    }

    /**
     * @param ProxyQueryInterface $query
     * @return ProxyQueryInterface
     */
    private function adjustCountryAccess(ProxyQueryInterface $query)
    {
        $query->andWhere('
            (
                (a.type = :countryType AND a.object_id IN (SELECT c.code FROM NS\SentinelBundle\Entity\Country c WHERE c.code IN (:countryIds) ) ) OR
                (a.type >= :siteType AND a.type <= :nlLabType AND a.object_id IN (SELECT s.code FROM NS\SentinelBundle\Entity\Site s WHERE s.country IN (:countries) ) )
            )
          ')
            ->setParameter('countryType', Role::COUNTRY)
            ->setParameter('countryIds', $this->getCountryIds())
            ->setParameter('countries', $this->getCountries())
            ->setParameter('siteType', Role::SITE)
            ->setParameter('nlLabType', Role::NL_LAB);

        return $query;
    }

    const REGIONS = 'admin.regions';
    const COUNTRIES = 'admin.countries';

    /**
     * @return array
     */
    private function getRegions()
    {
        $regions = array();
        $modelManager = $this->getModelManager()->getEntityManager('NS\SentinelBundle\Entity\Region');
        foreach ($this->getRegionsIds() as $regionId) {
            $regions[] = $modelManager->getReference('NS\SentinelBundle\Entity\Region', $regionId);
        }

        return $regions;
    }

    /**
     * @return array
     */
    private function getCountries()
    {
        $countries = array();
        $modelManager = $this->getModelManager()->getEntityManager('NS\SentinelBundle\Entity\Country');

        foreach ($this->getCountryIds() as $countryId) {
            $countries[] = $modelManager->getReference('NS\SentinelBundle\Entity\Country', $countryId);
        }

        return $countries;

    }

    /**
     * @return array|mixed
     */
    private function getRegionsIds()
    {
        $session = $this->getRequest()->getSession();
        if (!$session->has(self::REGIONS)) {
            $regionIds = $this->aclConverter->getObjectIdsForRole($this->tokenStorage->getToken(), 'ROLE_REGION');
            $session->set(self::REGIONS, $regionIds);

            return $regionIds;
        }

        return $session->get(self::REGIONS);
    }

    /**
     * @return array|mixed
     */
    private function getCountryIds()
    {
        $session = $this->getRequest()->getSession();

        if (!$session->has(self::COUNTRIES)) {
            $countryIds = $this->aclConverter->getObjectIdsForRole($this->tokenStorage->getToken(), 'ROLE_COUNTRY');

            return $countryIds;
        }

        return $session->get(self::COUNTRIES);
    }
}
