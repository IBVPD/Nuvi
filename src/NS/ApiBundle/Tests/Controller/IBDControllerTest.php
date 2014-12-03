<?php

namespace NS\ApiBundle\Tests\Controller;

use NS\ApiBundle\Tests\WebTestCase;
use \NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of IBDController
 *
 * @author gnat
 */
class IBDControllerTest extends WebTestCase
{
    const ID = 'CA-ALBCHLD-14-000001';

    public function testGetCase()
    {
        // add all your doctrine fixtures classes
        $classes = array(
            // classes implementing Doctrine\Common\DataFixtures\FixtureInterface
            'NS\SentinelBundle\DataFixtures\ORM\LoadUserData',
            'NS\SentinelBundle\DataFixtures\ORM\LoadRegionData',
            'NS\SentinelBundle\DataFixtures\ORM\LoadIBDCaseData',
            'NS\ApiBundle\DataFixtures\ORM\LoadApiClientData',
        );

        $this->loadFixtures($classes);

        $user  = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy(array(
            'email' => 'ca-api@noblet.ca'));
        $route = $this->getUrl('nsApiIbdGetCase', array('objId' => 'CA-ALBCHLD-14-000001'));

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
        $user  = $this->getUser();
        $route = $this->getUrl('nsApiIbdPatchCase', array('objId' => self::ID));

        $client = $this->createApiClient($user);
        $client->request('PATCH', $route, array(), array(), array(), '{"ibd":{"lastName":"Fabien"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $gRoute = $this->getUrl('nsApiIbdGetCase', array('objId' => self::ID));
        $this->assertEquals($response->headers->get('Location'), $gRoute, "The location matches the expected response");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD')->find(self::ID);
        $this->assertEquals("Fabien", $case->getLastName(), "Change has occurred");
        $this->assertTrue($case->getGender()->equal(\NS\SentinelBundle\Form\Types\Gender::MALE));
    }

    public function testPostCase()
    {
        $user  = $this->getUser();
        $route = $this->getUrl('nsApiIbdPostCase');

        $client = $this->createApiClient($user);
        $client->request('POST', $route, array(), array(), array(), '{"create_ibd":{"caseId":"ANewCaseId","type":1,"site":"ALBCHLD"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 201);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('CaseId', $decoded);
        $this->assertArrayNotHasKey('Lab', $decoded);
        $this->assertEquals("ANewCaseId", $decoded['CaseId']);
    }

    public function testLabCase()
    {
        $user  = $this->getUser();
        $route = $this->getUrl('nsApiIbdPatchLab', array('objId' => self::ID));

        $client = $this->createApiClient($user);
        $client->request('PATCH', $route, array(), array(), array(), '{"ibd_lab":{"csfId":"ANewCaseId","csfGramDone":0,"csfCultDone":0}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('CsfId', $decoded);
        $this->assertEquals("ANewCaseId", $decoded['CsfId']);
    }

    public function testGetRRLCase()
    {
        $user  = $this->getUser();
        $route = $this->getUrl('nsApiIbdGetRRL', array('objId' => self::ID));

        $client = $this->createApiClient($user);
        $client->request('GET', $route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Status', $decoded, print_r($decoded, true));
    }

    public function testRRLCase()
    {
        $user  = $this->getUser();
        $route = $this->getUrl('nsApiIbdPatchRRL', array('objId' => self::ID));

        $client = $this->createApiClient($user);
        $client->request('PATCH', $route, array(), array(), array(), '{"ibd_referencelab":{"labId":"ANewCaseId","sampleType":1}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('LabId', $decoded, print_r(array_keys($decoded), true));
        $this->assertEquals("ANewCaseId", $decoded['LabId']);
    }

    public function testGetNLCase()
    {
        $user  = $this->getUser();
        $route = $this->getUrl('nsApiIbdGetNL', array('objId' => self::ID));

        $client = $this->createApiClient($user);
        $client->request('GET', $route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Status', $decoded, print_r($decoded, true));
    }

    public function testNLCase()
    {
        $user  = $this->getUser();
        $route = $this->getUrl('nsApiIbdPatchNL', array('objId' => self::ID));

        $client = $this->createApiClient($user);
        $client->request('PATCH', $route, array(), array(), array(), '{"ibd_nationallab":{"labId":"ANewCaseId","sampleType":1}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('LabId', $decoded, print_r(array_keys($decoded), true));
        $this->assertEquals("ANewCaseId", $decoded['LabId']);
    }

    public function testPutCase()
    {
        $user  = $this->getUser();
        $route = $this->getUrl('nsApiIbdPutCase', array('objId' => self::ID));

        $client = $this->createApiClient($user);
        $client->request('PUT', $route, array(), array(), array(), '{"ibd":{"lastName":"Fabien","caseId":"12"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $gRoute = $this->getUrl('nsApiIbdGetCase', array('objId' => self::ID));
        $this->assertEquals($response->headers->get('Location'), $gRoute, "The location matches the expected response");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD')->find(self::ID);
        $this->assertEquals("Fabien", $case->getLastName(), "Change has occurred");
        $this->assertEquals(ArrayChoice::NO_SELECTION, $case->getGender()->getValue());
    }

    public function testPutCaseWithoutCaseId()
    {
        $user  = $this->getUser();
        $route = $this->getUrl('nsApiIbdPutCase', array('objId' => self::ID));

        $client = $this->createApiClient($user);
        $client->request('PUT', $route, array(), array(), array(), '{"rotavirus":{"lastName":"Fabien"');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 400);
    }

    private function getUser()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy(array(
                'email' => 'ca-api@noblet.ca'));
    }

}
