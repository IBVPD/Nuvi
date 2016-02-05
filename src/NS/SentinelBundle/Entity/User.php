<?php

namespace NS\SentinelBundle\Entity;

use \Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\Collections\Collection;
use \Doctrine\ORM\Mapping as ORM;
use \NS\SentinelBundle\Validators as LocalAssert;
use \Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use \Symfony\Component\Security\Core\User\AdvancedUserInterface;
use \Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="users",uniqueConstraints={@ORM\UniqueConstraint(name="email_idx",columns={"email"})})
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\UserRepository")
 * @LocalAssert\UserAcl()
 * @UniqueEntity("email")
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class User implements AdvancedUserInterface
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
     * @var string
     */
    private $plainPassword;

    /**
     *
     * @var \NS\SentinelBundle\Entity\ACL $acls
     * 
     * @ORM\OneToMany(targetEntity="\NS\SentinelBundle\Entity\ACL", mappedBy="user", fetch="EAGER",cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="id",referencedColumnName="user_id")
     */
    private $acls;

    /**
     * @var boolean
     *
     * @ORM\Column(name="admin", type="boolean")
     */
    private $admin = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="canCreateCases", type="boolean")
     */
    private $canCreateCases = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="canCreateLabs", type="boolean")
     */
    private $canCreateLabs = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="canCreateRRLLabs", type="boolean")
     */
    private $canCreateRRLLabs = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="canCreateNLLabs", type="boolean")
     */
    private $canCreateNLLabs = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = false;

    /**
     * @var string $language
     * @ORM\Column(name="language",type="string",nullable=true)
     */
    private $language;

    /**
     * @var ReferenceLab $referenceLab
     * @ORM\ManyToOne(targetEntity="ReferenceLab",inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $referenceLab;

    /**
     * @var int
     */
    private $ttl = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->acls = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

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
        if($this->salt === null || empty($this->salt)) {
            $this->resetSalt();
        }

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

    /**
     * Resets the salt
     */
    public function resetSalt()
    {
        $this->salt = User::_resetSalt(array($this->name,$this->email));
    }

    /**
     *
     * @param array $fields
     * @return string
     */
    static public function _resetSalt($fields = array())
    {
        return sha1(implode("",$fields).microtime());
    }

    /**
     *
     */
    public function eraseCredentials()
    {

    }

    /**
     *
     * @return boolean
     */
    public function isOnlyApi()
    {
        $roles = $this->getRoles();
        foreach ($roles as $role) {
            switch ($role) {
                case 'ROLE_REGION_API':
                case 'ROLE_COUNTRY_API':
                case 'ROLE_SITE_API':
                case 'ROLE_CAN_CREATE_CASE':
                case 'ROLE_CAN_CREATE_LAB':
                    break;
                default:
                    return false;
            }
        }

        return true;
    }

    /**
     *
     * @return boolean
     */
    public function isOnlyAdmin()
    {
        $roles = $this->getRoles();
        return (count($roles) == 1 && in_array('ROLE_ADMIN', $roles));
    }

    /**
     *
     * @return boolean
     */
    public function isOnlyImport()
    {
        $roles = $this->getRoles();
        foreach ($roles as $role) {
            switch ($role) {
                case 'ROLE_REGION_IMPORT':
                case 'ROLE_COUNTRY_IMPORT':
                case 'ROLE_SITE_IMPORT':
                case 'ROLE_CAN_CREATE_CASE':
                case 'ROLE_CAN_CREATE_LAB':
                    break;
                default:
                    return false;
            }
        }

        return true;
    }

    /**
     *
     * @param array $roles
     */
    public function adjustRoles(array $roles)
    {
        if (in_array('ROLE_SITE', $roles)) {
            $this->setCanCreateCases(true);
        }

        if (in_array('ROLE_RRL_LAB', $roles)) {
            $this->setCanCreateRRLLabs(true);
        }

        if (in_array('ROLE_NL_LAB', $roles)) {
            $this->setCanCreateNLLabs(true);
        }

        if (in_array('ROLE_LAB', $roles)) {
            $this->setCanCreateLabs(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = array();

        // what happens if this returns null??
        foreach($this->acls as $acl) {
            $roles = array_merge($roles,$acl->getType()->getAsCredential());
        }

        $this->adjustRoles($roles);

        if ($this->admin) {
            $roles[] = 'ROLE_ADMIN';
        }

        if ($this->canCreateCases) {
            $roles[] = 'ROLE_CAN_CREATE_CASE';
        }

        if ($this->canCreateLabs) {
            $roles[] = 'ROLE_CAN_CREATE_LAB';
        }

        if ($this->canCreateRRLLabs) {
            $roles[] = 'ROLE_CAN_CREATE_RRL_LAB';
        }

        if ($this->canCreateNLLabs) {
            $roles[] = 'ROLE_CAN_CREATE_NL_LAB';
        }

        return array_unique($roles);
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return boolean
     */
    public function isAccountNonExpired() 
    {
        return true;
    }

    /**
     * @return type
     */
    public function isAccountNonLocked() 
    {
        return $this->active;
    }

    /**
     * @return boolean
     */
    public function isCredentialsNonExpired() 
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function isEnabled() 
    {
        return $this->active;
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
     * @return Collection
     */
    public function getAcls()
    {
        return $this->acls;
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     * @return User
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean 
     */
    public function isAdmin()
    {
        return $this->admin;
    }

    /**
     *
     * @return cache time to live
     */
    public function getTTL()
    {
        return $this->ttl;
    }

    /**
     *
     * @param integer $ttl
     * @return \NS\SentinelBundle\Entity\User
     */
    public function setTTL($ttl)
    {
        $this->ttl = $ttl;
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     *
     * @param boolean $active
     * @return \NS\SentinelBundle\Entity\User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getCanCreate()
    {
        return ($this->canCreateCases || $this->canCreateLabs || $this->canCreateRRLLabs || $this->canCreateNLLabs);
    }

    /**
     * @return boolean
     */
    public function getCanCreateCases()
    {
        return $this->canCreateCases;
    }

    /**
     * @return boolean
     */
    public function getCanCreateLabs()
    {
        return $this->canCreateLabs;
    }

    /**
     * @return boolean
     */
    public function getCanCreateRRLLabs()
    {
        return $this->canCreateRRLLabs;
    }

    /**
     * @return boolean
     */
    public function getCanCreateNLLabs()
    {
        return $this->canCreateNLLabs;
    }

    /**
     * @param boolean $canCreateCases
     * @return \NS\SentinelBundle\Entity\User
     */
    public function setCanCreateCases($canCreateCases)
    {
        $this->canCreateCases = $canCreateCases;
        return $this;
    }

    /**
     * @param boolean $canCreateLabs
     * @return \NS\SentinelBundle\Entity\User
     */
    public function setCanCreateLabs($canCreateLabs)
    {
        $this->canCreateLabs = $canCreateLabs;
        return $this;
    }

    /**
     * @param boolean $canCreateRRLLabs
     * @return \NS\SentinelBundle\Entity\User
     */
    public function setCanCreateRRLLabs($canCreateRRLLabs)
    {
        $this->canCreateRRLLabs = $canCreateRRLLabs;
        return $this;
    }

    /**
     * @param boolean $canCreateNLLabs
     * @return \NS\SentinelBundle\Entity\User
     */
    public function setCanCreateNLLabs($canCreateNLLabs)
    {
        $this->canCreateNLLabs = $canCreateNLLabs;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function getReferenceLab()
    {
        return $this->referenceLab;
    }

    /**
     * @param \NS\SentinelBundle\Entity\ReferenceLab $referenceLab
     * @return \NS\SentinelBundle\Entity\User
     */
    public function setReferenceLab(ReferenceLab $referenceLab)
    {
        $this->referenceLab = $referenceLab;
        return $this;
    }

    /**
     * @return boolean
     */
    public function hasReferenceLab()
    {
        return ($this->referenceLab instanceof ReferenceLab);
    }
}
