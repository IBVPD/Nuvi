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
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
     */
    protected $user;

    public function getId()
    {
        return $this->id;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getUser()
    {
        return $this->user;
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

    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }
}
