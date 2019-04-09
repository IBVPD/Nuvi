<?php

namespace NS\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\AccessToken as BaseAccessToken;

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
     * @var int
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="Client",inversedBy="accessTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
     */
    protected $user;
}
