<?php

namespace NS\ApiBundle\Tests;

use \Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use \Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of WebTestCase
 *
 * @author gnat
 */
class WebTestCase extends BaseWebTestCase
{
    const ID = 'CA-ALBCHLD-14-000001';

    private $accessToken;

    public function createApiClient(UserInterface $user, array $options = array(), array $server = array())
    {
        $accessToken  = $this->getAccessToken($user);
        $serverParams = array_merge(array(
            'HTTP_ACCEPT'        => 'application/json',
            'Content-Type'       => 'application/json',
            'CONTENT_TYPE'       => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $accessToken['access_token']), $server);

        $this->assertContains($accessToken['access_token'], $serverParams['HTTP_AUTHORIZATION'], "Contains http authorization");

        return static::createClient($options, $serverParams);
    }

    public function getAccessToken(UserInterface $user)
    {
        $uname = $user->getUsername();
        if (isset($this->accessToken[$uname]) && $this->accessToken[$uname]['expires_at'] < time()) {
            return $this->accessToken[$uname]['access_token'];
        }

        $cont        = $this->getContainer();
        $em          = $cont->get('doctrine.orm.entity_manager');
        $oauth       = $cont->get('fos_oauth_server.server');
        $oauthClient = $em->getRepository('NSApiBundle:Client')->getForUser($user);

        $cont->get('fos_oauth_server.auth_code_manager')->deleteExpired();

        $this->assertNotNull($oauthClient, "OauthClient is not null");
        $this->assertNotEmpty($oauthClient, "OauthClient is not empty");
        $this->assertTrue(is_object($oauthClient[0]));

        $accessToken = $oauth->createAccessToken($oauthClient[0], $user);

        $this->assertNotNull($accessToken);
        $this->assertArrayHasKey('access_token', $accessToken);

        $accessToken['expires_at'] = time() + $accessToken['expires_in'];
        $this->accessToken[$uname] = $accessToken;

        return $accessToken;
    }

    public function assertJsonResponse($response, $statusCode = 200)
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
        $client = $this->createApiClient($user, array('HTTPS' => true));
        $client->followRedirects();

        return $client;
    }

    protected function getUser()
    {
        return $this->getContainer()
                ->get('doctrine.orm.entity_manager')
                ->getRepository('NSSentinelBundle:User')
                ->findOneBy(array('email' => 'ca-api@noblet.ca'));
    }
}
