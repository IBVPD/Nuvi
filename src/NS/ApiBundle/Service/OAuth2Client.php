<?php

namespace NS\ApiBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use NS\ApiBundle\Entity\Remote;
use OAuth2\Client;
use OAuth2\Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig_SimpleFunction;
use UnexpectedValueException;

class OAuth2Client extends AbstractExtension
{
    /** @var ObjectManager */
    private $entityMgr;

    /** @var Remote|null */
    protected $remote;

    /** @var Client|null */
    protected $client;

    public function __construct(ObjectManager $em)
    {
        $this->entityMgr = $em;
    }

    public function getRemote(): ?Remote
    {
        return $this->remote;
    }

    public function setRemote(Remote $remote): void
    {
        $this->remote = $remote;
        $this->client = new Client($remote->getClientId(), $remote->getClientSecret());
        $this->client->setAccessTokenType(Client::ACCESS_TOKEN_BEARER);
    }

    public function getAuthenticationUrl(Client $client = null, Remote $remote = null): string
    {
        if (($client !== null && $remote === null) || ($client === null && $remote !== null)) {
            throw new UnexpectedValueException("You can't provide only one parameter. Either pass two or none");
        }

        return $client ? $client->getAuthenticationUrl($remote->getAuthEndpoint(), $remote->getRedirectUrl()) : $this->client->getAuthenticationUrl($this->remote->getAuthEndpoint(), $this->remote->getRedirectUrl());
    }

    public function getAccessTokenByAuthorizationCode($code): bool
    {
        $this->getAccessToken(Client::GRANT_TYPE_AUTH_CODE, ['code' => $code,
            'redirect_uri' => $this->remote->getRedirectUrl()]);

        return true;
    }

    public function getAccessTokenByRefreshToken(): bool
    {
        if (!$this->remote->hasRefreshToken()) {
            throw new RuntimeException('No refresh token');
        }

        $this->getAccessToken(Client::GRANT_TYPE_REFRESH_TOKEN, ['refresh_token' => $this->remote->getRefreshToken()]);

        return true;
    }

    public function getAccessTokenByClientCredentials(): bool
    {
        $this->getAccessToken(Client::GRANT_TYPE_CLIENT_CREDENTIALS, []);

        return true;
    }

    /**
     *
     * @param integer $grant
     * @param array $params
     * @throws Exception
     */
    private function getAccessToken($grant, array $params): void
    {
        $response = $this->client->getAccessToken($this->remote->getTokenEndpoint(), $grant, $params);

        if (isset($response['result']['access_token'])) {
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
     * @return array
     */
    public function fetch($url)
    {
        if ($this->remote->isExpired()) {
            $this->getAccessTokenByRefreshToken();
        }

        $this->client->setAccessToken($this->remote->getAccessToken());
        $results = [$this->client->fetch($url)];

        if ($results[0]['code'] === Response::HTTP_UNAUTHORIZED) {
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

    public function getFunctions(): array
    {
        return [
            new TwigFunction('oauth_authenticate_path', [$this, 'getAuthenticationPath'], ['is_safe' => ['html']]),
        ];
    }

    public function getName(): string
    {
        return 'oauth_client';
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }
}
