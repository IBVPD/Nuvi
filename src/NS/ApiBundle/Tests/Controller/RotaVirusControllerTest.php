<?php

namespace NS\ApiBundle\Tests\Controller;

use NS\ApiBundle\Tests\WebTestCase;

/**
 * Description of RotaVirusController
 *
 * @author gnat
 */
class RotaVirusControllerTest extends WebTestCase
{
    public function testGetCase()
    {
         // add all your doctrine fixtures classes
        $classes = array(
            // classes implementing Doctrine\Common\DataFixtures\FixtureInterface
            'NS\SentinelBundle\DataFixtures\ORM\LoadUserData',
            'NS\SentinelBundle\DataFixtures\ORM\LoadRegionData',
            'NS\SentinelBundle\DataFixtures\ORM\LoadRotaVirusCaseData',
            'NS\ApiBundle\DataFixtures\ORM\LoadApiClientData',
        );

        $this->loadFixtures($classes);

        $user   = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy(array('email'=>'ca-api@noblet.ca'));
        $route  = $this->getUrl('ns_api_rotavirus_getrotacase',array('id'=>'CA-ALBCHLD-14-000001'));

        $client = $this->createApiClient($user);
        $client->request('GET', $route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = $response->getContent();
        $decoded = json_decode($content, true);

        $this->assertArrayHasKey('Id', $decoded);
        $this->assertEquals('CA-ALBCHLD-14-000001', $decoded['Id']);
    }
}
