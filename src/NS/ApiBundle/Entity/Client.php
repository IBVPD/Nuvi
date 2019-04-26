<?php

namespace NS\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use NS\SentinelBundle\Entity\User;

/**
 * Description of Client
 * @ORM\Entity(repositoryClass="NS\ApiBundle\Repository\ClientRepository")
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

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     *
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
     * @param User $user
     * @return Client
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getAccessTokens()
    {
        return $this->accessTokens;
    }

    /**
     *
     * @param array $accessTokens
     *
     * @return Client
     */
    public function setAccessTokens(array $accessTokens)
    {
        $this->accessTokens = $accessTokens;

        return $this;
    }

    /**
     *
     * @param AccessToken $token
     *
     * @return Client
     */
    public function addAccessToken(AccessToken $token)
    {
        $this->accessTokens->add($token);

        return $this;
    }

    /**
     *
     * @param AccessToken $token
     *
     * @return Client
     */
    public function removeAccessToken(AccessToken $token)
    {
        $this->accessTokens->remove($token);

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function hasAccessToken()
    {
        return (count($this->accessTokens) > 0);
    }
}
