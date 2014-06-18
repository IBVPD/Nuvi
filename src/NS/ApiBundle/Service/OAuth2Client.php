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
        if ($code !== null)
            $this->params['code'] = $code;

        $response = $this->client->getAccessToken($this->tokenEndpoint, $this->grant, $this->params);

        if(isset($response['result']) && isset($response['result']['access_token']))
        {
            $accessToken = $response['result']['access_token'];
            $this->client->setAccessToken($accessToken);
            return $accessToken;
        }

        throw new Exception(sprintf('Unable to obtain Access Token. Response from the Server: %s\n,%s\n%s\n%s', var_export($response),$this->tokenEndpoint,$this->grant,print_r($this->params,true)));
    }

    public function fetch($url)
    {
        return $this->client->fetch($url);
    }
}