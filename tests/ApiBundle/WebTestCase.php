<?php

namespace NS\ApiBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use NS\ApiBundle\Entity\Client;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of WebTestCase
 *
 * @author gnat
 */
class WebTestCase extends BaseWebTestCase
{
    const ID = 'CA-ALBCHLD-14-000001';

    private $accessToken;

    public function createApiClient(UserInterface $user, array $options = [], array $server = []): \Symfony\Bundle\FrameworkBundle\Client
    {
        $accessToken  = $this->getAccessToken($user);
        $serverParams = array_merge([
            'HTTP_ACCEPT'        => 'application/json',
            'Content-Type'       => 'application/json',
            'CONTENT_TYPE'       => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $accessToken['access_token']], $server);

        $this->assertContains($accessToken['access_token'], $serverParams['HTTP_AUTHORIZATION'], 'Contains http authorization');

        return static::createClient($options, $serverParams);
    }

    public function getAccessToken(UserInterface $user)
    {
        $username = $user->getUsername();
        if (isset($this->accessToken[$username]) && $this->accessToken[$username]['expires_at'] < time()) {
            return $this->accessToken[$username]['access_token'];
        }

        $cont        = $this->getContainer();
        $em          = $cont->get('doctrine.orm.entity_manager');
        $oauth       = $cont->get('fos_oauth_server.server');
        $oauthClient = $em->getRepository(Client::class)->getForUser($user);

        $cont->get('fos_oauth_server.auth_code_manager')->deleteExpired();

        $this->assertNotNull($oauthClient, 'OauthClient is not null');
        $this->assertNotEmpty($oauthClient, 'OauthClient is not empty');
        $this->assertInternalType('object', $oauthClient[0]);

        $accessToken = $oauth->createAccessToken($oauthClient[0], $user);

        $this->assertNotNull($accessToken);
        $this->assertArrayHasKey('access_token', $accessToken);

        $accessToken['expires_at'] = time() + $accessToken['expires_in'];
        $this->accessToken[$username] = $accessToken;

        return $accessToken;
    }

    public function assertJsonResponse($response, $statusCode = 200): void
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(), $response->getContent()
        );

        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'), $response->headers
        );
    }

    protected function getClient()
    {
        $user   = $this->getUser();
        $client = $this->createApiClient($user, ['HTTPS' => true]);
        $client->followRedirects();

        return $client;
    }

    protected function getUser()
    {
        return $this->getContainer()
                ->get('doctrine.orm.entity_manager')
                ->getRepository('NSSentinelBundle:User')
                ->findOneBy(['email' => 'ca-api@noblet.ca']);
    }
}
