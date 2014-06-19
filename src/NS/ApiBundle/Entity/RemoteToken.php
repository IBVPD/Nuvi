<?php

namespace NS\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of RemoteToken
 *
 * @author gnat
 * @ORM\Entity
 */
class RemoteToken
{
    /**
     * @var integer $id
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $accessToken
     * @ORM\Column(name="accessToken",type="string")
     */
    private $accessToken;

    /**
     * @var string $refreshToken
     * @ORM\Column(name="refreshToken",type="string")
     */
    private $refreshToken;

    /**
     * @var integer $expiry
     * @ORM\Column(name="expiry",type="integer")
     */
    private $expiry;

    /**
     * @var string $remoteEndpoint
     * @ORM\Column(name="remoteEndpoint",type="string")
     */
    private $remoteEndpoint;

    /**
     * @var NS\SentinelBundle\Entity\User $user
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
     */
    protected $user;

    public function getId()
    {
        return $this->id;
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

    public function getUser()
    {
        return $this->user;
    }

    public function getRemoteEndpoint()
    {
        return $this->remoteEndpoint;
    }

    public function setRemoteEndpoint($remoteEndpoint)
    {
        $this->remoteEndpoint = $remoteEndpoint;
        return $this;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
        $this->expiry = $expiry-5;
        return $this;
    }

    public function isExpired()
    {
        return (time() > $this->expiry);
    }
}
