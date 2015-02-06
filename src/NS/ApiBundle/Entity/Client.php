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
     * @return \NS\ApiBundle\Entity\Client
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
     * @param NS\SentinelBundle\Entity\User $user
     * @return \NS\ApiBundle\Entity\Client
     */
    public function setUser(\NS\SentinelBundle\Entity\User $user)
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
     * @return \NS\ApiBundle\Entity\Client
     */
    public function setAccessTokens(array $accessTokens)
    {
        $this->accessTokens = $accessTokens;

        return $this;
    }

    /**
     *
     * @param \NS\ApiBundle\Entity\AccessToken $token
     * @return \NS\ApiBundle\Entity\Client
     */
    public function addAccessToken(AccessToken $token)
    {
        $this->accessTokens->add($token);

        return $this;
    }

    /**
     *
     * @param \NS\ApiBundle\Entity\AccessToken $token
     * @return \NS\ApiBundle\Entity\Client
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
