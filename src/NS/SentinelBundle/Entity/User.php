<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Validators as LocalAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Email
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
     * @Assert\Valid()
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
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\Region")
     * @ORM\JoinColumn(referencedColumnName="code",nullable=true)
     */
    private $region;

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
        if ($this->salt === null || empty($this->salt)) {
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
        $this->salt = User::_resetSalt([$this->name, $this->email]);
    }

    /**
     *
     * @param array $fields
     * @return string
     */
    public static function _resetSalt($fields = [])
    {
        return sha1(implode("", $fields).microtime());
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
    public function isOnlyAdmin()
    {
        return ($this->getRoles() == ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
    }

    /**
     * @var array
     */
    private $roles;

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        if (!$this->roles) {
            $roles = [];

            foreach ($this->acls as $acl) {
                $roles = array_merge($roles, $acl->getCredentials());
            }

            if ($this->admin) {
                if (empty($roles)) {
                    $roles = ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN'];
                } elseif (in_array('ROLE_REGION', $roles)) {
                    $roles = array_merge($roles, ['ROLE_ADMIN', 'ROLE_SONATA_REGION_ADMIN']);
                } elseif (in_array('ROLE_COUNTRY', $roles)) {
                    $roles = array_merge($roles, ['ROLE_ADMIN', 'ROLE_SONATA_COUNTRY_ADMIN']);
                }
            }

            if ($this->region instanceof Region) {
                $roles[] = sprintf('ROLE_%s',strtoupper($this->region->getCode()));
            }

            $this->roles = array_unique($roles);
        }

        return $this->roles;
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
     * @return bool
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

    /**
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param Region $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }
}
