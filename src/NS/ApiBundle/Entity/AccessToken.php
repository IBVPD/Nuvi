<?php

namespace NS\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\AccessToken as BaseAccessToken;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of AccessToken
 * @ORM\Entity
 * @ORM\Table(name="access_tokens")
 * @author gnat
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class AccessToken extends BaseAccessToken
{
    /**
     * @var integer $id
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Client $client
     * @ORM\ManyToOne(targetEntity="Client",inversedBy="accessTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
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
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
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
     * @param UserInterface $user
     * @return \NS\ApiBundle\Entity\AccessToken
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     *
     * @param ClientInterface $client
     * @return \NS\ApiBundle\Entity\AccessToken
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }
}
