<?php

namespace NS\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;
use NS\SentinelBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="refresh_tokens")
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class RefreshToken extends BaseRefreshToken
{
    /**
     * @var integer|null
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
     */
    protected $user;
}
