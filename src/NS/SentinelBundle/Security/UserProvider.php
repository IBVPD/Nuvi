<?php

namespace NS\SentinelBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use \Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\Common\Persistence\ObjectManager;
use NS\SentinelBundle\Entity\User;

/**
 * Description of UserProvider
 *
 * @author gnat
 */
class UserProvider implements UserProviderInterface
{
    private $em;
    
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function loadUserByUsername($username) 
    {
        try 
        {
            $user = $this->em->createQuery("SELECT u,a FROM NS\SentinelBundle\Entity\User u LEFT JOIN u.acls a WHERE u.email = :username")
                             ->setParameter('username',$username)
                             ->getSingleResult();
            
            if (null === $user)
                throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));

            $user->setTTL(time()+3600);
            return $user;
        }
        catch(Doctrine\ORM\NonUniqueResultException $e)
        {
            throw new UsernameNotFoundException(sprintf("Username %s is not unique",$username));
        }
    }

    public function refreshUser(UserInterface $user) 
    {
        if (!$user instanceof User) 
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));

        return (time() > $user->getTTL()) ? $this->loadUserByUsername($user->getUsername()): $user;
    }

    public function supportsClass($class) 
    {
        return $class == 'NS\SentinelBundle\Entity\User';
    }

//put your code here
}
