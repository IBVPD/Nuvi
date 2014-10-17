<?php

namespace NS\ApiBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Client
 * @ORM\Entity(repositoryClass="NS\ApiBundle\Repository\Client")
 * @ORM\Table(name="api_clients")
 * @author gnat
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Client extends BaseClient
{
    /**
     * @var integer $id
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $name
     * @ORM\Column(name="name",type="string")
     */
    private $name;

    /**
     * @var AccessToken $accessTokens
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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getAccessTokens()
    {
        return $this->accessTokens;
    }

    public function setAccessTokens(array $accessTokens)
    {
        $this->accessTokens = $accessTokens;

        return $this;
    }

    public function addAccessToken(AccessToken $token)
    {
        $this->accessTokens->add($token);

        return $this;
    }

    public function removeAccessToken(AccessToken $token)
    {
        $this->accessTokens->remove($token);

        return $this;
    }

    public function hasAccessToken()
    {
        return (count($this->accessTokens) > 0);
    }
}
