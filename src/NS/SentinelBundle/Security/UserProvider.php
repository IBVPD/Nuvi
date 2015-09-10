<?php

namespace NS\SentinelBundle\Security;

use \Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\ORM\NonUniqueResultException;
use \Doctrine\ORM\NoResultException;
use \NS\SentinelBundle\Entity\User;
use \Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use \Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use \Symfony\Component\Security\Core\User\UserInterface;
use \Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Description of UserProvider
 *
 * @author gnat
 */
class UserProvider implements UserProviderInterface
{
    private $entityMgr;

    /**
     *
     * @param ObjectManager $entityMgr
     */
    public function __construct(ObjectManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     *
     * @param string $username
     * @return UserInterface
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        try {
            $user = $this->entityMgr->createQuery("SELECT u,a,l FROM NS\SentinelBundle\Entity\User u LEFT JOIN u.acls a LEFT JOIN u.referenceLab l WHERE u.email = :username")
                ->setParameter('username', $username)
                ->getSingleResult();

            if (null === $user) {
                throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
            }

            $user->setTTL(time() + 3600);
            return $user;
        }
        catch (NonUniqueResultException $e) {
            throw new UsernameNotFoundException(sprintf("Username %s is not unique", $username), 0, $e);
        }
        catch (NoResultException $e) {
            throw new UsernameNotFoundException("User not found", 0, $e);
        }
    }

    /**
     *
     * @param UserInterface $user
     * @return UserInterface
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return (time() > $user->getTTL()) ? $this->loadUserByUsername($user->getUsername()) : $user;
    }

    /**
     *
     * @param string $class
     * @return boolean
     */
    public function supportsClass($class)
    {
        return $class == 'NS\SentinelBundle\Entity\User';
    }
}
