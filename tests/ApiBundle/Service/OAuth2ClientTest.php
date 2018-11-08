<?php

namespace NS\ApiBundle\Tests\Service;

use NS\ApiBundle\Service\OAuth2Client;

/**
 * Description of OAuth2ClientTest
 *
 * @author gnat
 */
class OAuth2ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $entityMgr = $this->getEntityManager();
        $authClient = new OAuth2Client($entityMgr);
        $this->assertEquals('oauth_client', $authClient->getName());
    }

    public function testSetRemote()
    {
        $entityMgr = $this->getEntityManager();
        $remote    = new \NS\ApiBundle\Entity\Remote();
        $remote->setClientId('clientId1234156');
        $remote->setClientSecret(md5('clientId1234156'));

        $authClient = new OAuth2Client($entityMgr);
        $authClient->setRemote($remote);

        $client = $authClient->getClient();
        $this->assertEquals($authClient->getRemote(), $remote);
        $this->assertEquals($remote->getClientId(), $client->getClientId());
        $this->assertEquals($remote->getClientSecret(), $client->getClientSecret());
    }

    public function testGetAuthenticationUrl()
    {
        $entityMgr = $this->getEntityManager();
        $remote    = new \NS\ApiBundle\Entity\Remote();
        $remote->setClientId('clientId1234156');
        $remote->setClientSecret(md5('clientId1234156'));
        $remote->setAuthEndpoint('http://localhost/auth');
        $remote->setTokenEndpoint('http://localhost/token');

        $authClient = new OAuth2Client($entityMgr);
        $authClient->setRemote($remote);

        $url = $authClient->getAuthenticationUrl();
        $this->assertNotNull($url);
        $this->assertEquals('http://localhost/auth?response_type=code&client_id=clientId1234156', $url);

        $client = $authClient->getClient();

        $url = $authClient->getAuthenticationUrl($client, $remote);
        $this->assertNotNull($url);
        $this->assertEquals('http://localhost/auth?response_type=code&client_id=clientId1234156', $url);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testGetAuthenticationUrlException()
    {
        $entityMgr  = $this->getEntityManager();
        $remote     = new \NS\ApiBundle\Entity\Remote();
        $authClient = new OAuth2Client($entityMgr);

        $authClient->getAuthenticationUrl(null, $remote);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testGetAuthenticationUrlSecondException()
    {
        $entityMgr  = $this->getEntityManager();
        $remote     = new \NS\ApiBundle\Entity\Remote();
        $authClient = new OAuth2Client($entityMgr);
        $authClient->setRemote($remote);
        $client     = $authClient->getClient();

        $authClient->getAuthenticationUrl($client);
    }

    public function testGetAuthenticationPath()
    {
        $entityMgr = $this->getEntityManager();
        $remote    = new \NS\ApiBundle\Entity\Remote();
        $remote->setClientId('clientId1234156');
        $remote->setClientSecret(md5('clientId1234156'));
        $remote->setAuthEndpoint("http://localhost/auth");
        $remote->setTokenEndpoint("http://localhost/token");

        $authClient = new OAuth2Client($entityMgr);
        $authClient->setRemote($remote);

        $urlOne = $authClient->getAuthenticationPath();
        $this->assertNotNull($urlOne);
        $this->assertEquals('http://localhost/auth?response_type=code&client_id=clientId1234156', $urlOne);

        $urlTwo = $authClient->getAuthenticationPath($remote);
        $this->assertNotNull($urlTwo);
        $this->assertEquals('http://localhost/auth?response_type=code&client_id=clientId1234156', $urlTwo);
    }

    private function getEntityManager()
    {
        return $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
                ->disableOriginalConstructor()
                ->getMock();
    }
}
