<?php

namespace NS\ApiBundle\Tests\Controller;

use NS\ApiBundle\Tests\WebTestCase;
use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of RotaVirusController
 *
 * @author gnat
 */
class RotaVirusControllerTest extends WebTestCase
{
    private $email = array('email' => 'ca-api@noblet.ca');

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

        $user  = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy($this->email);
        $route = $this->getUrl('nsApiRotaGetCase', array('objId' => 'CA-ALBCHLD-14-000001'));

        $client = $this->createApiClient($user);
        $client->request('GET', $route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = $response->getContent();
        $decoded = json_decode($content, true);

        $this->assertArrayHasKey('Id', $decoded);
        $this->assertEquals('CA-ALBCHLD-14-000001', $decoded['Id']);
    }

    public function testPatchCase()
    {
        $id    = 'CA-ALBCHLD-14-000001';
        $user  = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy($this->email);
        $route = $this->getUrl('nsApiRotaPatchCase', array('objId' => $id));

        $client = $this->createApiClient($user);
        $client->request('PATCH', $route, array(), array(), array(), '{"rotavirus":{"lastName":"Fabien"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $gRoute = $this->getUrl('nsApiRotaGetCase', array('objId' => $id));
        $this->assertEquals($response->headers->get('Location'), $gRoute, "The location matches the expected response");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus')->find($id);
        $this->assertEquals("Fabien", $case->getLastName(), "Change has occurred");
    }

    public function testPostCase()
    {
        $user  = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy($this->email);
        $route = $this->getUrl('nsApiRotaPostCase');

        $client = $this->createApiClient($user);
        $client->request('POST', $route, array(), array(), array(), '{"create_rotavirus":{"caseId":"123","type":"1","site":"ALBCHLD"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 201);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('CaseId', $decoded);
        $this->assertArrayNotHasKey('Lab', $decoded);
        $this->assertEquals("123", $decoded['CaseId']);
    }

    public function testLabCase()
    {
        $id    = 'CA-ALBCHLD-14-000001';
        $user  = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy($this->email);
        $route = $this->getUrl('nsApiRotaPatchLab', array('objId' => $id));

        $client = $this->createApiClient($user);
        $client->request('PATCH', $route, array(), array(), array(), '{"rotavirus_lab":{"adequate":1,"elisaDone":1}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Adequate', $decoded, print_r($decoded, true));
//        $this->assertEquals("ANewCaseId", $decoded['Adequate']);
    }

    public function testPutCase()
    {
        $id    = 'CA-ALBCHLD-14-000001';
        $user  = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy($this->email);
        $route = $this->getUrl('nsApiRotaPutCase', array('objId' => $id));

        $client = $this->createApiClient($user);
        $client->request('PUT', $route, array(), array(), array(), '{"rotavirus":{"lastName":"Fabien","caseId":"12"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $gRoute = $this->getUrl('nsApiRotaGetCase', array('objId' => $id));
        $this->assertEquals($response->headers->get('Location'), $gRoute, "The location matches the expected response");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus')->find($id);
        $this->assertEquals("Fabien", $case->getLastName(), "Change has occurred");
        $this->assertEquals(ArrayChoice::NO_SELECTION, $case->getGender()->getValue());
    }

    public function testPutCaseWithoutCaseId()
    {
        $id    = 'CA-ALBCHLD-14-000001';
        $user  = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy($this->email);
        $route = $this->getUrl('nsApiRotaPutCase', array('objId' => $id));

        $client = $this->createApiClient($user);
        $client->request('PUT', $route, array(), array(), array(), '{"rotavirus":{"lastName":"Fabien"');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 400);
    }

}
