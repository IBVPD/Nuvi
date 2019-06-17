<?php

namespace NS\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\AuthCode as BaseAuthCode;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="auth_codes")
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class AuthCode extends BaseAuthCode
{
    /**
     * @var integer
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Client
     * @ORM\OneToOne(targetEntity="Client")
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
     */
    protected $user;
}
