<?php

namespace NS\ApiBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Util\Codes;
use NS\ApiBundle\Entity\Remote;
use OAuth2\Client;
use OAuth2\Exception;
use RuntimeException;
use Twig_Extension;
use Twig_SimpleFunction;
use UnexpectedValueException;

/**
 * Description of OAuth2Client
 *
 * @author gnat
 */
class OAuth2Client extends Twig_Extension
{
    private $entityMgr;

    /**
     * @var Remote $remote
     */
    protected $remote;
    protected $client;

    /**
     *
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->entityMgr = $em;
    }

    /**
     *
     * @return Remote
     */
    public function getRemote()
    {
        return $this->remote;
    }

    /**
     *
     * @param Remote $remote
     *
     * @return OAuth2Client
     */
    public function setRemote(Remote $remote)
    {
        $this->remote = $remote;
        $this->client = new Client($remote->getClientId(), $remote->getClientSecret());
        $this->client->setAccessTokenType(Client::ACCESS_TOKEN_BEARER);

        return $this;
    }

    /**
     *
     * @param Client $client
     * @param Remote $remote
     * @return string
     * @throws UnexpectedValueException
     */
    public function getAuthenticationUrl(Client $client = null, Remote $remote = null)
    {
        if (($client !== null && $remote === null) || ($client === null && $remote !== null)) {
            throw new UnexpectedValueException("You can't provide only one parameter. Either pass two or none");
        }

        return $client ? $client->getAuthenticationUrl($remote->getAuthEndpoint(), $remote->getRedirectUrl()) : $this->client->getAuthenticationUrl($this->remote->getAuthEndpoint(), $this->remote->getRedirectUrl());
    }

    /**
     *
     * @param string $code
     * @return boolean
     */
    public function getAccessTokenByAuthorizationCode($code)
    {
        $this->getAccessToken(Client::GRANT_TYPE_AUTH_CODE, ['code' => $code,
            'redirect_uri' => $this->remote->getRedirectUrl()]);

        return true;
    }

    /**
     *
     * @return boolean
     * @throws RuntimeException
     */
    public function getAccessTokenByRefreshToken()
    {
        if (!$this->remote->hasRefreshToken()) {
            throw new RuntimeException("No refresh token");
        }

        $this->getAccessToken(Client::GRANT_TYPE_REFRESH_TOKEN, ['refresh_token' => $this->remote->getRefreshToken()]);

        return true;
    }

    /**
     *
     * @return boolean
     */
    public function getAccessTokenByClientCredentials()
    {
        $this->getAccessToken(Client::GRANT_TYPE_CLIENT_CREDENTIALS, []);

        return true;
    }

    /**
     *
     * @param integer $grant
     * @param array $params
     * @return array
     * @throws Exception
     */
    private function getAccessToken($grant, array $params)
    {
        $response = $this->client->getAccessToken($this->remote->getTokenEndpoint(), $grant, $params);

        if (isset($response['result']) && isset($response['result']['access_token'])) {
            $this->remote->updateFromArray($response['result']);
            $this->entityMgr->persist($this->remote);
            $this->entityMgr->flush();

            return;
        }

        throw new Exception(sprintf('Unable to obtain Access Token. Response from the Server: "%s"', var_export($response, true)));
    }

    /**
     *
     * @param string $url
     * @return string
     */
    public function fetch($url)
    {
        if ($this->remote->isExpired()) {
            $this->getAccessTokenByRefreshToken();
        }

        $this->client->setAccessToken($this->remote->getAccessToken());
        $results = [$this->client->fetch($url)];

        if ($results[0]['code'] == Codes::HTTP_UNAUTHORIZED) {
            $this->getAccessTokenByRefreshToken();
            $this->client->setAccessToken($this->remote->getAccessToken());

            return $this->client->fetch($url);
        }

        return $results;
    }

    /**
     *
     * @param Remote $remote
     * @return string
     */
    public function getAuthenticationPath(Remote $remote = null)
    {
        if ($remote === null && $this->remote) {
            $remote = $this->remote;
        }

        $client = new Client($remote->getClientId(), $remote->getClientSecret());

        return $this->getAuthenticationUrl($client, $remote);
    }

    /**
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('oauth_authenticate_path', [$this, 'getAuthenticationPath'], ['is_safe' => ['html']]),
        ];
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'oauth_client';
    }

    /**
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
