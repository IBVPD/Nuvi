<?php

namespace NS\ApiBundle\Entity;
use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of RefreshToken
 * @ORM\Entity
 * @author gnat
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

    public function getId()
    {
        return $this->id;
    }

    public function getClient()
    {
        return $this->client;
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
