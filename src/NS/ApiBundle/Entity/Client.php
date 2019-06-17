<?php

namespace NS\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use NS\SentinelBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="NS\ApiBundle\Repository\ClientRepository")
 * @ORM\Table(name="api_clients")
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Client extends BaseClient
{
    /**
     * @var integer
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="name",type="string")
     */
    private $name;

    /**
     * @var AccessToken[]|Collection
     * @ORM\OneToMany(targetEntity="AccessToken",mappedBy="client")
     */
    private $accessTokens;

    /**
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
     */
    private $user;

    public function __toString()
    {
        return $this->name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getAccessTokens(): Collection
    {
        return $this->accessTokens;
    }

    public function setAccessTokens(array $accessTokens): void
    {
        $this->accessTokens = new ArrayCollection($accessTokens);
    }

    public function addAccessToken(AccessToken $token): void
    {
        $this->accessTokens->add($token);
    }

    public function removeAccessToken(AccessToken $token): void
    {
        if ($this->accessTokens->contains($token)) {
            $this->accessTokens->removeElement($token);
        }
    }

    public function hasAccessToken(): bool
    {
        return !$this->accessTokens->isEmpty();
    }
}
