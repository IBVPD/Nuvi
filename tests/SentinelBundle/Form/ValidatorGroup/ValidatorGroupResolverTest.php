<?php

namespace NS\SentinelBundle\Tests\Form\ValidatorGroup;

use Doctrine\Common\Persistence\ObjectRepository;
use NS\SentinelBundle\Entity\ACL;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\User;
use NS\SentinelBundle\Form\Types\Role;
use NS\SentinelBundle\Form\ValidatorGroup\ValidatorGroupResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use NS\SentinelBundle\Repository\RegionRepository;
use Symfony\Component\Form\FormInterface;

class ValidatorGroupResolverTest extends TestCase
{
    /** @var TokenStorageInterface|MockObject */
    private $tokenStorage;

    /** @var EntityManagerInterface|MockObject */
    private $entityMgr;

    /** @var ObjectRepository|MockObject */
    private $repository;

    public function testIsCallable(): void
    {
        $resolver = new ValidatorGroupResolver($this->entityMgr, $this->tokenStorage);
        $this->assertTrue(is_callable($resolver));
    }

    public function testObjectIdsFromAcls(): void
    {
        $acl = new ACL();
        $acl->setObjectId('AMRO');
        $acl->setType(new Role(Role::REGION));
        $user = new User();
        $user->addAcl($acl);

        $resolver = new ValidatorGroupResolver($this->entityMgr,$this->tokenStorage);
        $results = $resolver->getObjectsFromAcls($user->getAcls());
        $this->assertEquals(['AMRO'],$results );
    }

    public function testObjectNames(): void
    {
        $region = new Region();
        $region->setCode('AMRO');
        $region->setName('PAHO');

        $resolver = new ValidatorGroupResolver($this->entityMgr,$this->tokenStorage);
        $results = $resolver->getObjectNames([$region]);
        $this->assertEquals(['AMRO'],$results);
    }

    /**
     * @depends testObjectNames
     * @depends testObjectIdsFromAcls
     */
    public function testRegionUser(): void
    {
        $region = new Region();
        $region->setCode('AMRO');
        $region->setName('PAHO');

        $acl = new ACL();
        $acl->setObjectId('AMRO');
        $acl->setType(new Role(Role::REGION));
        $user = new User();
        $user->addAcl($acl);

        $this->assertTrue(in_array('ROLE_REGION',$user->getRoles()));

        $token = new UsernamePasswordToken($user, '', 'provider', $user->getRoles());
        $this->tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn($token);

        $this->repository
            ->expects($this->never())
            ->method('getByCountryIds');

        $this->repository
            ->expects($this->never())
            ->method('getBySiteIds');

        $resolver = new ValidatorGroupResolver($this->entityMgr, $this->tokenStorage);

        $form = $this->createMock(FormInterface::class);
        $this->assertEquals(['Default','AMRO'], $resolver($form));
    }

    /**
     * @depends testObjectNames
     * @depends testObjectIdsFromAcls
     */
    public function testCountryUser(): void
    {
        $region = new Region();
        $region->setCode('AMRO');
        $region->setName('PAHO');

        $acl = new ACL();
        $acl->setObjectId('CAN');
        $acl->setType(new Role(Role::COUNTRY));
        $user = new User();
        $user->addAcl($acl);

        $this->assertTrue(in_array('ROLE_COUNTRY',$user->getRoles()));

        $token = new UsernamePasswordToken($user, '', 'provider', $user->getRoles());
        $this->tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn($token);

        $this->repository
            ->expects($this->once())
            ->method('getByCountryIds')
            ->with(['CAN'])
            ->willReturn([$region]);

        $this->repository
            ->expects($this->never())
            ->method('getBySiteIds');

        $resolver = new ValidatorGroupResolver($this->entityMgr, $this->tokenStorage);

        $form = $this->createMock(FormInterface::class);
        $this->assertEquals(['Default','AMRO'], $resolver($form));
    }

    /**
     * @depends testObjectNames
     * @depends testObjectIdsFromAcls
     */
    public function testSiteUser(): void
    {
        $region = new Region();
        $region->setCode('AMRO');
        $region->setName('PAHO');

        $acl = new ACL();
        $acl->setObjectId('CAN');
        $acl->setType(new Role(Role::NL_LAB));
        $user = new User();
        $user->addAcl($acl);

        $token = new UsernamePasswordToken($user, '', 'provider', $user->getRoles());
        $this->tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn($token);

        $this->repository
            ->expects($this->once())
            ->method('getBySiteIds')
            ->with(['CAN'])
            ->willReturn([$region]);

        $this->repository
            ->expects($this->never())
            ->method('getByCountryIds');

        $resolver = new ValidatorGroupResolver($this->entityMgr, $this->tokenStorage);

        $form = $this->createMock(FormInterface::class);
        $this->assertEquals(['Default','AMRO'], $resolver($form));
    }

    /**
     * @param $acls
     * @return array
     */
    public function getObjectsFromAcls($acls): array
    {
        $ids = [];
        /** @var ACL $acl */
        foreach ($acls as $acl) {
            $ids[] = $acl->getObjectId();
        }

        return $ids;
    }

    /**
     * @param array $objects
     * @return array
     */
    public function getObjectNames($objects): array
    {
        $names = [];
        foreach((array)$objects as $object) {
            $names[] = $object->getName();
        }

        return $names;
    }

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->entityMgr = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->getMockBuilder(RegionRepository::class)
            ->setMethods(['getByCountryIds','getBySiteIds'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityMgr->expects($this->any())
            ->method('getRepository')
            ->with('NSSentinelBundle:Region')
            ->willReturn($this->repository);
    }
}
