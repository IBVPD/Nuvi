<?php

namespace NS\ApiBundle\Tests\Controller;

use NS\ApiBundle\Tests\WebTestCase;

/**
 * Description of ApiControllerTest
 *
 * @author gnat
 */
class ApiControllerTest extends WebTestCase
{
    public function testTest()
    {
        // add all your doctrine fixtures classes
        $classes = array(
            // classes implementing Doctrine\Common\DataFixtures\FixtureInterface
            'NS\SentinelBundle\DataFixtures\ORM\LoadUserData',
            'NS\SentinelBundle\DataFixtures\ORM\LoadRegionData',
            'NS\ApiBundle\DataFixtures\ORM\LoadApiClientData',
        );

        $this->loadFixtures($classes);

        $route  = $this->getUrl('ns_api_api_test');

        $client = $this->getClient();
        $client->request('GET',$route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        
        $content = $response->getContent();
        $decoded = json_decode($content, true);

        $this->assertTrue(isset($decoded['username']),print_r($decoded,true));
        $this->assertArrayHasKey('username', $decoded);
        $this->assertEquals('ca-api@noblet.ca', $decoded['username']);
    }

    public function testSites()
    {
        $route  = $this->getUrl('ns_api_api_sites');

        $client = $this->getClient();
        $client->request('GET',$route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = $response->getContent();
        $decoded = json_decode($content, true);

        $this->assertArrayHasKey('sites', $decoded);
        $this->assertCount(3, $decoded['sites'],print_r($decoded,true));
    }
}
