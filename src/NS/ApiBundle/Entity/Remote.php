<?php

namespace NS\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="remote_oauth_providers")
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Remote
{
    /**
     * @var int|null
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name",type="string",nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="clientId",type="string")
     */
    private $clientId;

    /**
     * @var string
     * @ORM\Column(name="clientSecret",type="string")
     */
    private $clientSecret;

    /**
     * @var string
     * @ORM\Column(name="tokenEndpoint",type="string")
     * @Assert\Url()
     */
    private $tokenEndpoint;

    /**
     * @var string
     * @ORM\Column(name="authEndpoint",type="string")
     * @Assert\Url()
     */
    private $authEndpoint;

    /**
     * @var string
     * @ORM\Column(name="redirectUrl",type="string")
     * @Assert\Url()
     */
    private $redirectUrl;

    /**
     * @var string
     * @ORM\Column(name="accessToken",type="string",nullable=true)
     */
    private $accessToken;

    /**
     * @var string
     * @ORM\Column(name="refreshToken",type="string",nullable=true)
     */
    private $refreshToken;

    /**
     * @var integer
     * @ORM\Column(name="expiry",type="integer",nullable=true)
     */
    private $expiry;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    protected $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTokenEndpoint(): ?string
    {
        return $this->tokenEndpoint;
    }

    public function getAuthEndpoint(): ?string
    {
        return $this->authEndpoint;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function getExpiry(): int
    {
        return $this->expiry;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    public function setExpiry(int $expiry): void
    {
        $this->expiry = ($expiry <= 5000) ? time() + $expiry - 5 : $expiry - 5;
    }

    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function setTokenEndpoint(string $tokenEndpoint): void
    {
        $this->tokenEndpoint = $tokenEndpoint;
    }

    public function setAuthEndpoint(string $authEndpoint): void
    {
        $this->authEndpoint = $authEndpoint;
    }

    public function setRedirectUrl(string $redirectUrl): void
    {
        $this->redirectUrl = $redirectUrl;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function isExpired(): bool
    {
        return (time() > $this->expiry);
    }

    public function hasAccessToken(): bool
    {
        return ($this->accessToken !== null);
    }

    public function hasRefreshToken(): bool
    {
        return ($this->refreshToken !== null);
    }

    public function updateFromArray(array $result): void
    {
        $this->setAccessToken($result['access_token']);
        $this->setRefreshToken($result['refresh_token']);
        $this->setExpiry($result['expires_in']);
    }
}
