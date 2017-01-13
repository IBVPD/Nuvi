<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 03/06/16
 * Time: 1:27 PM.
 */
namespace NS\SentneilBundle\Token;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;

class TokenGenerator
{
    /**
     * @var
     */
    private $issuer;

    /**
     * @var int|null
     */
    private $expiration = 172800;

    /**
     * @var
     */
    private $id;

    /**
     * @var
     */
    private $key;

    /**
     * TokenGenerator constructor.
     *
     * @param $id
     * @param $key
     * @param $issuer
     * @param $expiration
     */
    public function __construct($id, $key, $issuer, $expiration = null)
    {
        $this->id = $id;
        $this->key = $key;
        $this->issuer = $issuer;

        if ($expiration) {
            $this->expiration = $expiration;
        }
    }

    /**
     * @param $expiration
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
    }

    /**
     * @param $uId
     * @param $email
     * @param array|null $extraData
     *
     * @return \Lcobucci\JWT\Token
     */
    public function getToken($uId, $email, array $extraData = null)
    {
        $signer = new Sha256();

        $builder = new Builder();
        $builder->setIssuer($this->issuer)
            ->setAudience($this->issuer)
            ->setId($this->id)
            ->setNotBefore(time())
            ->setExpiration(time() + $this->expiration)
            ->set('userId', $uId)
            ->set('email', $email);

        if ($extraData) {
            $builder->set('extra', serialize($extraData));
        }

        return $builder
            ->sign($signer, $this->key)
            ->getToken();
    }

    /**
     * @param $tokenStr
     *
     * @return array
     *
     * @throws InvalidTokenException
     */
    public function decryptToken($tokenStr)
    {
        $token = $this->parseToken($tokenStr);
        $extra = $token->hasClaim('extra') ? unserialize($token->getClaim('extra')) : null;

        return array($token->getClaim('userId'), $token->getClaim('email'), $extra);
    }

    /**
     * @param string $tokenStr
     *
     * @return bool
     */
    public function isValid($tokenStr)
    {
        try {
            $this->parseToken($tokenStr);

            return true;
        } catch (InvalidTokenException $exception) {
            return false;
        }
    }

    /**
     * @param string $tokenStr
     *
     * @return \Lcobucci\JWT\Token
     *
     * @throws InvalidTokenException
     */
    private function parseToken($tokenStr)
    {
        $signer = new Sha256();
        $token = (new Parser())->parse($tokenStr);

        if (!$token->verify($signer, $this->key)) {
            throw new InvalidTokenException('Invalid token');
        }

        $data = new ValidationData();
        $data->setId($this->id);
        $data->setIssuer($this->issuer);
        $data->setAudience($this->issuer);

        if (!$token->validate($data)) {
            throw new InvalidTokenException('Invalid token');
        }

        return $token;
    }
}
