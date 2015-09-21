<?php

namespace NS\ApiBundle\Tests\Controller;

use \NS\ApiBundle\Tests\WebTestCase;
use \NS\SentinelBundle\Form\Types\Gender;
use \NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of RotaVirusController
 *
 * @author gnat
 */
class RotaVirusControllerTest extends WebTestCase
{
    const ID = 'CA-ALBCHLD-15-000081';

    public function testGetCase()
    {
        $route  = $this->getRoute();
        $client = $this->getClient();
        $client->request('GET', $route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = $response->getContent();
        $decoded = json_decode($content, true);

        $this->assertArrayHasKey('Id', $decoded);
        $this->assertEquals(self::ID, $decoded['Id']);
    }

    public function testPatchCase()
    {
        $route  = $this->getRoute('nsApiRotaPatchCase');
        $client = $this->getClient();
        $client->request('PATCH', $route, array(), array(), array(), '{"rotavirus":{"lastName":"Fabien","gender":"2"}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus')->find(self::ID);
        $this->assertEquals("Fabien", $case->getLastName(), "Change has occurred");
        $this->assertTrue($case->getGender()->equal(Gender::FEMALE));
    }

    public function testPostCase()
    {
        $route  = $this->getUrl('nsApiRotaPostCase');
        $client = $this->getClient();
        $client->request('POST', $route, array(), array(), array(), '{"create_case":{"caseId":"123","type":"1","site":"ALBCHLD"}}');

        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
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
        $route  = $this->getUrl('nsApiRotaPatchLab', array('objId' => self::ID));
        $client = $this->getClient();
        $client->request('PATCH', $route, array(), array(), array(), '{"rotavirus_lab":{"adequate":1,"elisaDone":1}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Adequate', $decoded, print_r($decoded, true));
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function testGetRRLCase()
    {
        $client = $this->getClient();
        $client->request('GET', $this->getRoute('nsApiRotaGetRRL'));

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        json_decode($response->getContent(), true);
    }

    public function testPatchRRLCase()
    {
        $route  = $this->getRoute('nsApiRotaPatchRRL');
        $client = $this->getClient();
        $client->request('PATCH', $route, array(), array(), array(), '{"rotavirus_referencelab":{"labId":"ANewCaseId"}}');

        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

// Because our user can't patch the RRL, no data is set/changed.
//        $client->request('GET', $this->getRoute('nsApiRotaGetRRL'));
//        $response = $client->getResponse();
//        $this->assertJsonResponse($response, 200);
//        $decoded  = json_decode($response->getContent(), true);
//
//        $this->assertArrayHasKey('LabId', $decoded, print_r(array_keys($decoded), true));
//        $this->assertEquals("ANewCaseId", $decoded['LabId']);
    }

    public function testPutRRLCase()
    {
        $route  = $this->getRoute('nsApiRotaPutRRL');
        $client = $this->getClient();
        $client->request('PUT', $route, array(), array(), array(), '{"rotavirus_referencelab":{"labId":"ANewCaseId"}}');

        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

// Because our user can't patch the RRL, no data is set/changed.
//        $client->request('GET', $this->getRoute('nsApiRotaGetRRL'));
//        $response = $client->getResponse();
//        $this->assertJsonResponse($response, 200);
//        $decoded  = json_decode($response->getContent(), true);
//
//        $this->assertArrayHasKey('LabId', $decoded);
//        $this->assertEquals("ANewCaseId", $decoded['LabId']);
    }

    public function testGetNLCase()
    {
        $route  = $this->getUrl('nsApiRotaGetNL', array('objId' => self::ID));
        $client = $this->getClient();
        $client->request('GET', $route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Status', $decoded, print_r($decoded, true));
    }

    public function testPatchNLCase()
    {
        $route  = $this->getRoute('nsApiRotaPatchNL');
        $client = $this->getClient();
        $client->request('PATCH', $route, array(), array(), array(), '{"rotavirus_nationallab":{"labId":"ANewCaseId"}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $this->getRoute('nsApiRotaGetNL'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('LabId', $decoded, print_r(array_keys($decoded), true));
        $this->assertEquals("ANewCaseId", $decoded['LabId']);
    }

    public function testPutNLCase()
    {
        $route  = $this->getRoute('nsApiRotaPutNL');
        $client = $this->getClient();
        $client->request('PUT', $route, array(), array(), array(), '{"rotavirus_nationallab":{"labId":"ANewCaseId"}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $this->getRoute('nsApiRotaGetNL'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('LabId', $decoded);
        $this->assertEquals("ANewCaseId", $decoded['LabId']);
    }

    public function testPutCase()
    {
        $route  = $this->getUrl('nsApiRotaPutCase', array('objId' => self::ID));
        $client = $this->getClient();
        $client->request('PUT', $route, array(), array(), array(), '{"rotavirus":{"lastName":"Fabien","caseId":"12"}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus')->find(self::ID);
        $this->assertEquals("Fabien", $case->getLastName(), "Change has occurred");
        $this->assertEquals(ArrayChoice::NO_SELECTION, $case->getGender()->getValue());
    }

    public function testPutCaseWithoutCaseId()
    {
        $route  = $this->getUrl('nsApiRotaPutCase', array('objId' => self::ID));
        $client = $this->getClient();
        $client->request('PUT', $route, array(), array(), array(), '{"rotavirus":{"lastName":"Fabien"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 400);
    }

    private function getRoute($route = 'nsApiRotaGetCase', $id = null)
    {
        $objId = $id === null ? self::ID : $id;

        return $this->getUrl($route, array('objId' => $objId));
    }
}
