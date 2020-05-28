<?php

namespace NS\SentinelBundle\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnexpectedResultException;
use NS\SecurityBundle\Role\ACLConverter;
use NS\SentinelBundle\Entity\ACL;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Entity\User;
use NS\SentinelBundle\Form\Types\Role;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserAdmin extends AbstractAdmin
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
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ]
            ]);
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
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => false,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
                ]
            )
            ->add('active', null, ['required' => false])
            ->add('admin', null, ['required' => false])
            ->add('referenceLab', null, ['required' => false, 'label' => 'admin.form-reference-lab'])
            ->add('acls', 'sonata_type_collection', ['by_reference' => true, 'label' => 'Access Restrictions', 'required' => false], ['edit' => 'inline', 'inline' => 'table']);
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

        $this->handleAcl($user);

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

        $this->handleAcl($user);

        return $user;
    }

    private function handleAcl(User $user)
    {
        if ($user->getAcls()) {
            $entityManager = $this->getModelManager()->getEntityManager(Site::class);

            foreach ($user->getAcls() as $acl) {
                $acl->setUser($user);
                try {
                    switch ($acl->getType()->getValue()) {
                        case Role::SITE:
                        case Role::LAB:
                            /** @var Site $site */
                            $site = $entityManager
                                    ->getRepository(Site::class)
                                    ->createQueryBuilder('s')
                                    ->addSelect('r,c')
                                    ->innerJoin('s.country','c')
                                    ->innerJoin('c.region','r')
                                    ->where('s.code = :siteId')
                                    ->setParameter('siteId',$acl->getObjectId())
                                    ->getQuery()
                                    ->getSingleResult();

                            $user->setRegion($site->getCountry()->getRegion());
                            break;
                        case Role::NL_LAB:
                        case Role::RRL_LAB:
                        case Role::COUNTRY:
                            /** @var Country $country */
                            $country = $entityManager
                                ->getRepository(Country::class)
                                ->createQueryBuilder('c')
                                ->addSelect('r')
                                ->innerJoin('c.region','r')
                                ->where('c.code = :countryId')
                                ->setParameter('countryId',$acl->getObjectId())
                                ->getQuery()
                                ->getSingleResult();

                            $user->setRegion($country->getRegion());
                            break;
                        case Role::REGION:
                            $region = $entityManager->getReference(Region::class, $acl->getObjectId());

                            $user->setRegion($region);
                            break;
                    }
                } catch(UnexpectedResultException $exception) {

                }
            }
        }
    }

    /**
     * @param string $context
     * @return ProxyQueryInterface
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery();
        $query->leftJoin(sprintf('%s.acls', $query->getRootAlias()), 'a');

        $user = $this->tokenStorage->getToken()->getUser();

        // Super admins see all
        if ($user->isOnlyAdmin()) {
            return $query;
        }

        if (in_array('ROLE_SONATA_REGION_ADMIN', $user->getRoles())) {
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
                ( a.type IN (:countryTypes) AND a.object_id IN (SELECT c.code FROM NS\SentinelBundle\Entity\Country c WHERE c.region IN (:regions) ) ) OR
                ( a.type IN (:siteTypes) AND a.object_id IN (SELECT s.code FROM NS\SentinelBundle\Entity\Site s INNER JOIN s.country ct WHERE ct.region IN (:regions) ) )
            )
          ')
            ->setParameter('regionType', Role::REGION)
            ->setParameter('regionIds', $this->getRegions())
            ->setParameter('regions', $this->getRegions())
            ->setParameter('countryTypes', [Role::COUNTRY, Role::RRL_LAB, Role::NL_LAB])
            ->setParameter('siteTypes', [Role::SITE, Role::LAB]);

        return $query;
    }

    /**
     * @param ProxyQueryInterface $query
     * @return ProxyQueryInterface
     */
    private function adjustCountryAccess(ProxyQueryInterface $query): ProxyQueryInterface
    {
        $query->andWhere('
            (
                (a.type IN (:countryTypes) AND a.object_id IN (SELECT c.code FROM NS\SentinelBundle\Entity\Country c WHERE c.code IN (:countryIds) ) ) OR
                ( a.type IN (:siteTypes) AND a.object_id IN (SELECT s.code FROM NS\SentinelBundle\Entity\Site s INNER JOIN s.country ct WHERE ct.region IN (:regions) ) )
            )
          ')
            ->setParameter('countryTypes', [Role::COUNTRY, Role::RRL_LAB, Role::NL_LAB])
            ->setParameter('countryIds', $this->getCountryIds())
            ->setParameter('regions', $this->getRegions())
            ->setParameter('siteTypes', [Role::SITE, Role::LAB]);

        return $query;
    }

    public const REGIONS = 'admin.regions';
    public const COUNTRIES = 'admin.countries';

    private function getRegions(): array
    {
        $regions = [];
        $modelManager = $this->getModelManager()->getEntityManager(Region::class);
        foreach ($this->getRegionsIds() as $regionId) {
            $regions[] = $modelManager->getReference(Region::class, $regionId);
        }

        return $regions;
    }

    private function getCountries(): array
    {
        $countries = [];
        $modelManager = $this->getModelManager()->getEntityManager(Country::class);

        foreach ($this->getCountryIds() as $countryId) {
            $countries[] = $modelManager->getReference(Country::class, $countryId);
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
