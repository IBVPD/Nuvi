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
     * @var ACL $acls
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

    /** @var int */
    private $ttl = 0;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\Region")
     * @ORM\JoinColumn(referencedColumnName="code",nullable=true)
     */
    private $region;

    public function __construct()
    {
        $this->acls = new ArrayCollection();
        $this->resetSalt();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getSalt(): string
    {
        if ($this->salt === null || empty($this->salt)) {
            $this->resetSalt();
        }

        return $this->salt;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPlainPassword(string $password): void
    {
        $this->plainPassword = $password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Resets the salt
     */
    public function resetSalt(): void
    {
        $this->salt = self::_resetSalt([$this->name, $this->email]);
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

    public function isOnlyAdmin(): bool
    {
        return ($this->getRoles() == ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
    }

    /** @var array */
    private $roles;

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        if (!$this->roles) {
            $roles = [];

            /** @var ACL $acl */
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

    public function getUsername(): string
    {
        return $this->email;
    }

    public function isAccountNonExpired(): bool
    {
        return true;
    }

    public function isAccountNonLocked(): bool
    {
        return $this->active;
    }

    public function isCredentialsNonExpired(): bool
    {
        return true;
    }

    public function isEnabled(): bool
    {
        return $this->active ?? false;
    }

    public function addAcl(ACL $acl): void
    {
        $this->acls[] = $acl;
    }

    public function removeAcl(ACL $acl): void
    {
        $this->acls->removeElement($acl);
    }

    public function getAcls(): Collection
    {
        return $this->acls;
    }

    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }

    public function isAdmin(): bool
    {
        return $this->admin ?? false;
    }

    public function getTTL(): int
    {
        return $this->ttl;
    }

    public function setTTL(int $ttl): void
    {
        $this->ttl = $ttl;
    }

    public function isActive(): bool
    {
        return $this->active ?? false;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return ReferenceLab
     */
    public function getReferenceLab()
    {
        return $this->referenceLab;
    }

    public function setReferenceLab(ReferenceLab $referenceLab): void
    {
        $this->referenceLab = $referenceLab;
    }

    public function hasReferenceLab(): bool
    {
        return ($this->referenceLab instanceof ReferenceLab);
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): void
    {
        $this->region = $region;
    }
}
