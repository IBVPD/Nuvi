<?php

namespace NS\ApiBundle\Entity;
use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of RefreshToken
 * @ORM\Entity
 * @ORM\Table(name="refresh_tokens")
 * @author gnat
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class RefreshToken extends BaseRefreshToken
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
     * @return \NS\ApiBundle\Entity\RefreshToken
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     *
     * @param ClientInterface $client
     * @return \NS\ApiBundle\Entity\RefreshToken
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }
}
