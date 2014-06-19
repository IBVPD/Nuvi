<?php

namespace NS\ApiBundle\Service;

use OAuth2\Client;
use OAuth2\Exception;

/**
 * Description of OAuth2Client
 *
 * @author gnat
 */
class OAuth2Client
{
    protected $client;
    protected $authEndpoint;
    protected $tokenEndpoint;
    protected $redirectUrl;
    protected $grant;
    protected $params;

    public function __construct(Client $client, $authEndpoint, $tokenEndpoint, $redirectUrl, $grant, $params)
    {
        $this->client        = $client;
        $this->client->setAccessTokenType(Client::ACCESS_TOKEN_BEARER);

        $this->authEndpoint  = $authEndpoint;
        $this->tokenEndpoint = $tokenEndpoint;
        $this->redirectUrl   = $redirectUrl;
        $this->grant         = $grant;
        $this->params        = $params;
    }

    public function getAuthenticationUrl()
    {
        return $this->client->getAuthenticationUrl($this->authEndpoint, $this->redirectUrl);
    }

    public function getAccessToken($code = null)
    {
        switch($this->grant)
        {
            case 'authorization_code':
                $this->params['code']          = $code;
                $this->params['redirectUrl']   = $this->redirectUrl;
                break;
            case 'refresh_token':
                $this->params['refresh_token'] = $code;
                break;
        }

        $response = $this->client->getAccessToken($this->tokenEndpoint, $this->grant, $this->params);

        if(isset($response['result']) && isset($response['result']['access_token']))
        {
            $accessToken = $response['result']['access_token'];
            $this->client->setAccessToken($accessToken);
            return $response['result'];
        }

        throw new Exception(sprintf('Unable to obtain Access Token. Response from the Server: "%s"', var_export($response,true)));
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    public function fetch($url)
    {
        return $this->client->fetch($url);
    }
}