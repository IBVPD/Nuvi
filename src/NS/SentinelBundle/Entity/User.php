<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use NS\SecurityBundle\Model\SecuredEntityInterface;
use NS\SentinelBundle\Form\Types\Role;

/**
 * User
 *
 * @ORM\Table(name="users",uniqueConstraints={@ORM\UniqueConstraint(name="email_idx",columns={"email"})})
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\User")
 */
class User implements AdvancedUserInterface, SecuredEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @Assert\Length(
     *              min = "6",
     *              minMessage = "Your password must be at least 6 characters")
     *
     * @var type
     */
    private $plainPassword;

    /**
     *
     * @var ACL $acls
     * 
     * @ORM\OneToMany(targetEntity="ACL", mappedBy="user", fetch="EAGER",cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="id",referencedColumnName="user_id")
     */
    private $acls;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="isAdmin", type="boolean")
     */
    private $isAdmin = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive = false;
    
    private $ttl = 0;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function resetSalt()
    {
        $this->salt = User::_resetSalt(array($this->name,$this->email));
    }

    static public function _resetSalt($fields = array())
    {
        return sha1(implode("",$fields).microtime());
    }

    public function eraseCredentials()
    {

    }

    public function getRoles()
    {
        $roles = array();

        // what happens if this returns null??
        foreach($this->acls as $acl)
            $roles = array_merge($roles,$acl->getType()->getAsCredential());

        if($this->isAdmin)
            $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function isAccountNonExpired() 
    {
        return true;
    }

    public function isAccountNonLocked() 
    {
        return $this->isActive;
    }

    public function isCredentialsNonExpired() 
    {
        return true;
    }

    public function isEnabled() 
    {
        return $this->isActive;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->acls = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add acls
     *
     * @param \NS\SentinelBundle\Entity\ACL $acls
     * @return User
     */
    public function addAcl(\NS\SentinelBundle\Entity\ACL $acls)
    {
        $this->acls[] = $acls;
    
        return $this;
    }

    /**
     * Remove acls
     *
     * @param \NS\SentinelBundle\Entity\ACL $acls
     */
    public function removeAcl(\NS\SentinelBundle\Entity\ACL $acls)
    {
        $this->acls->removeElement($acls);
    }

    /**
     * Get acls
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAcls()
    {
        return $this->acls;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     * @return User
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    
        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean 
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }
    
    public function getTTL()
    {
        return $this->ttl;
    }

    public function setTTL($ttl)
    {
        $this->ttl = $ttl;
        return $this;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getACLObjectIdsForRole($irole)
    {
        $object_ids = array();

        try
        {
            $role = new Role($irole);
        }
        catch(\UnexpectedValueException $e)
        {
            return null;
        }

        foreach($this->acls as $acl)
        {
            if($acl->getType()->equal($role)) // found an object id for this role
                $object_ids[] = $acl->getObjectId();
        }

        return $object_ids;        
    }
}