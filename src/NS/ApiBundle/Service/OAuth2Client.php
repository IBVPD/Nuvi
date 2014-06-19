<?php

namespace NS\ApiBundle\Service;

use OAuth2\Client;
use OAuth2\Exception;
use NS\ApiBundle\Entity\Remote;
use \Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of OAuth2Client
 *
 * @author gnat
 */
class OAuth2Client
{
    private $em;

    /**
     *
     * @var Remote $remote
     */
    protected $remote;
    protected $client;
    protected $grant;
    protected $params;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function getRemote()
    {
        return $this->remote;
    }

    public function setRemote(Remote $remote)
    {
        $this->remote = $remote;
    
        $this->client = new Client($remote->getClientId(),$remote->getClientSecret());
        $this->client->setAccessTokenType(Client::ACCESS_TOKEN_BEARER);

        return $this;
    }

//    public function __construct(Client $client, $authEndpoint, $tokenEndpoint, $redirectUrl, $grant, $params)
//    {
//        $this->client        = $client;
//        $this->client->setAccessTokenType(Client::ACCESS_TOKEN_BEARER);
//
//        $this->grant         = $grant;
//        $this->params        = $params;
//    }

    public function getAuthenticationUrl()
    {
        return $this->client->getAuthenticationUrl($this->remote->getAuthEndpoint(), $this->remote->getRedirectUrl());
    }

    public function getAccessTokenByAuthorizationCode($code)
    {
        $this->_getAccessToken(Client::GRANT_TYPE_AUTH_CODE, array('code'=>$code,'redirect_uri'=>$this->remote->getRedirectUrl()));

        return true;
    }

    public function getAccessTokenByRefreshToken()
    {
        if(!$this->remote->hasRefreshToken())
            throw new \RuntimeException("No refresh token");

        $this->_getAccessToken(Client::GRANT_TYPE_REFRESH_TOKEN, array('refresh_token'=>$this->remote->getRefreshToken()));

        return true;
    }

    public function getAccessTokenByClientCredentials()
    {
        $this->_getAccessToken(Client::GRANT_TYPE_CLIENT_CREDENTIALS, array());

        return true;
    }

    private function _getAccessToken($grant, $params)
    {
//        if($grant !== Client::GRANT_TYPE_REFRESH_TOKEN && $this->remote->hasAccessToken() && !$this->remote->isExpired())
//            return;

        $response = $this->client->getAccessToken($this->remote->getTokenEndpoint(), $grant, $params);

        if(isset($response['result']) && isset($response['result']['access_token']))
        {
            $this->remote->updateFromArray($response['result']);
            $this->em->persist($this->remote);
            $this->em->flush();

            return;
        }

        throw new Exception(sprintf('Unable to obtain Access Token. Response from the Server: "%s"', var_export($response,true)));
    }

    public function fetch($url)
    {
        if($this->remote->isExpired())
            $this->getAccessTokenByRefreshToken();

        $this->client->setAccessToken($this->remote->getAccessToken());
        $r = array($this->client->fetch($url));

        if($r[0]['code'] == \FOS\RestBundle\Util\Codes::HTTP_UNAUTHORIZED)
        {
            $this->getAccessTokenByRefreshToken();
            $this->client->setAccessToken($this->remote->getAccessToken());

            return $this->client->fetch($url);
        }

        return $r;
    }
}