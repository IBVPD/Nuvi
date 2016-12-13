<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 19/05/16
 * Time: 10:49 AM
 */

namespace NS\SentinelBundle\Form\ValidatorGroup;

use Doctrine\Common\Persistence\ObjectManager;
use NS\SentinelBundle\Entity\ACL;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ValidatorGroupResolver
{
    /**
     * @var ObjectManager
     */
    private $entityMgr;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * ValidatorGroupResolver constructor.
     * @param ObjectManager $entityMgr
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ObjectManager $entityMgr, TokenStorageInterface $tokenStorage)
    {
        $this->entityMgr = $entityMgr;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @inheritDoc
     *
     * @param array|null $groups
     * @param FormInterface $form
     *
     * @return array
     */
    public function __invoke(FormInterface $form)
    {
        return array_merge(['Default'], $this->getRegionNames());
    }

    /**
     * @return array
     */
    public function getRegionNames()
    {
        $user      = $this->tokenStorage->getToken()->getUser();
        $roles     = $user->getRoles();
        $objectIds = $this->getObjectsFromAcls($user->getAcls());

        if (in_array('ROLE_REGION', $roles)) {
            return $objectIds;
        }

        $repo = $this->entityMgr->getRepository('NSSentinelBundle:Region');

        if (in_array('ROLE_COUNTRY', $roles)) {
            $objects = $repo->getByCountryIds($objectIds);
        } else {
            $objects = $repo->getBySiteIds($objectIds);
        }

        return $this->getObjectNames($objects);
    }

    /**
     * @param $acls
     * @return array
     */
    public function getObjectsFromAcls($acls)
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
    public function getObjectNames($objects)
    {
        $names = [];
        foreach ((array)$objects as $object) {
            $names[] = $object->getCode();
        }

        return $names;
    }
}
