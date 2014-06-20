<?php

namespace NS\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of Remote
 *
 * @author gnat
 * @ORM\Entity
 * @ORM\Table(name="remote_oauth_providers")
 */
class Remote
{
    /**
     * @var integer $id
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $clientId
     * @ORM\Column(name="clientId",type="string")
     */
    private $clientId;

    /**
     * @var string $clientSecret
     * @ORM\Column(name="clientSecret",type="string")
     */
    private $clientSecret;

    /**
     * @var string $tokenEndpoint
     * @ORM\Column(name="tokenEndpoint",type="string")
     */
    private $tokenEndpoint;

    /**
     * @var string $authEndpoint
     * @ORM\Column(name="authEndpoint",type="string")
     */
    private $authEndpoint;

    /**
     * @var string $redirectUrl
     * @ORM\Column(name="redirectUrl",type="string")
     */
    private $redirectUrl;

    /**
     * @var string $accessToken
     * @ORM\Column(name="accessToken",type="string",nullable=true)
     */
    private $accessToken;

    /**
     * @var string $refreshToken
     * @ORM\Column(name="refreshToken",type="string",nullable=true)
     */
    private $refreshToken;

    /**
     * @var integer $expiry
     * @ORM\Column(name="expiry",type="integer",nullable=true)
     */
    private $expiry;

    /**
     * @var \NS\SentinelBundle\Entity\User $user
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    protected $user;

    public function __construct()
    {
        $this->tokens = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTokenEndpoint()
    {
        return $this->tokenEndpoint;
    }

    public function getAuthEndpoint()
    {
        return $this->authEndpoint;
    }

    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    public function getTokens()
    {
        return $this->tokens;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function getExpiry()
    {
        return $this->expiry;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function setExpiry($expiry)
    {
        $this->expiry = ($expiry <= 5000) ? time()+$expiry-5:$expiry-5;
        return $this;
    }

    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    public function setTokenEndpoint($tokenEndpoint)
    {
        $this->tokenEndpoint = $tokenEndpoint;
        return $this;
    }

    public function setAuthEndpoint($authEndpoint)
    {
        $this->authEndpoint = $authEndpoint;
        return $this;
    }

    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    public function setUser(\NS\SentinelBundle\Entity\User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function isExpired()
    {
        return (time() > $this->expiry);
    }

    public function hasAccessToken()
    {
        return (!is_null($this->accessToken));
    }

    public function hasRefreshToken()
    {
        return (!is_null($this->refreshToken));
    }

    public function updateFromArray(array $r)
    {
        $this->setAccessToken($r['access_token']);
        $this->setRefreshToken($r['refresh_token']);
        $this->setExpiry($r['expires_in']);
    }
}
