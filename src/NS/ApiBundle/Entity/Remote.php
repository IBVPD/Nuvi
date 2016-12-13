<?php

namespace NS\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Remote
 *
 * @author gnat
 * @ORM\Entity
 * @ORM\Table(name="remote_oauth_providers")
 * @SuppressWarnings(PHPMD.ShortVariable)
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
     * @var string $name
     * @ORM\Column(name="name",type="string",nullable=false)
     */
    private $name;

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
     * @Assert\Url()
     */
    private $tokenEndpoint;

    /**
     * @var string $authEndpoint
     * @ORM\Column(name="authEndpoint",type="string")
     * @Assert\Url()
     */
    private $authEndpoint;

    /**
     * @var string $redirectUrl
     * @ORM\Column(name="redirectUrl",type="string")
     * @Assert\Url()
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

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getTokenEndpoint()
    {
        return $this->tokenEndpoint;
    }

    /**
     *
     * @return string
     */
    public function getAuthEndpoint()
    {
        return $this->authEndpoint;
    }

    /**
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     *
     * @return NS\SentinelBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     *
     * @return integer
     */
    public function getExpiry()
    {
        return $this->expiry;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     * @return \NS\ApiBundle\Entity\Remote
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @param string $accessToken
     * @return \NS\ApiBundle\Entity\Remote
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     *
     * @param string $refreshToken
     * @return \NS\ApiBundle\Entity\Remote
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     *
     * @param integer $expiry
     * @return \NS\ApiBundle\Entity\Remote
     */
    public function setExpiry($expiry)
    {
        $this->expiry = ($expiry <= 5000) ? time()+$expiry-5:$expiry-5;
        return $this;
    }

    /**
     *
     * @param string $clientSecret
     * @return \NS\ApiBundle\Entity\Remote
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     *
     * @param string $clientId
     * @return \NS\ApiBundle\Entity\Remote
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     *
     * @param string $tokenEndpoint
     * @return \NS\ApiBundle\Entity\Remote
     */
    public function setTokenEndpoint($tokenEndpoint)
    {
        $this->tokenEndpoint = $tokenEndpoint;
        return $this;
    }

    /**
     *
     * @param string $authEndpoint
     * @return \NS\ApiBundle\Entity\Remote
     */
    public function setAuthEndpoint($authEndpoint)
    {
        $this->authEndpoint = $authEndpoint;
        return $this;
    }

    /**
     *
     * @param string $redirectUrl
     * @return \NS\ApiBundle\Entity\Remote
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    /**
     *
     * @param \NS\SentinelBundle\Entity\User $user
     * @return \NS\ApiBundle\Entity\Remote
     */
    public function setUser(\NS\SentinelBundle\Entity\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isExpired()
    {
        return (time() > $this->expiry);
    }

    /**
     *
     * @return boolean
     */
    public function hasAccessToken()
    {
        return ($this->accessToken !== null);
    }

    /**
     *
     * @return boolean
     */
    public function hasRefreshToken()
    {
        return ($this->refreshToken !== null);
    }

    /**
     *
     * @param array $result
     * @return \NS\ApiBundle\Entity\Remote
     */
    public function updateFromArray(array $result)
    {
        $this->setAccessToken($result['access_token']);
        $this->setRefreshToken($result['refresh_token']);
        $this->setExpiry($result['expires_in']);

        return $this;
    }
}
