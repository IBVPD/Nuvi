<?php

namespace NS\ApiBundle\Tests\Controller;

use NS\ApiBundle\Tests\WebTestCase;
use NS\SentinelBundle\Form\Types\Gender;
use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of RotaVirusController
 *
 * @author gnat
 */
class RotaVirusControllerTest extends WebTestCase
{
    const ID = 'CA-ALBCHLD-15-000089';

    public function testGetCase()
    {
        $route  = $this->getRoute();
        $client = $this->getClient();
        $client->request('GET', $route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);

        $content = $response->getContent();
        $decoded = json_decode($content, true);

        $this->assertArrayHasKey('id', $decoded);
        $this->assertEquals(self::ID, $decoded['id']);
    }

    public function testPatchCase()
    {
        $route  = $this->getRoute('nsApiRotaPatchCase');
        $client = $this->getClient();
        $client->request('PATCH', $route, [], [], [], '{"case":{"lastName":"Fabien","gender":"2"}}');

        $response = $client->getResponse();
        if ($response->getStatusCode() == 500) {
            file_put_contents('/tmp/nsApiRotaPatchCase.log', $response->getContent());
        }

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
        $client->request('POST', $route, [], [], [], '{"create":{"caseId":"123","type":"1","site":"ALBCHLD"}}');

        $response = $client->getResponse();
        if ($response->getStatusCode() != 201) {
            file_put_contents('/tmp/nsApiRotaPostCase.log', print_r($response->headers,true).$response->getContent());
        }

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->headers->has('Location'), "We have a location header ".print_r($response->headers,true));

        $client->request('GET', $response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('case_id', $decoded);
        $this->assertArrayNotHasKey('lab', $decoded);
        $this->assertEquals("123", $decoded['case_id']);
    }

    public function testLabCase()
    {
        $route  = $this->getUrl('nsApiRotaPatchLab', ['objId' => self::ID]);
        $client = $this->getClient();
        $client->request('PATCH', $route, [], [], [], '{"site_lab":{"adequate":1,"elisaDone":1}}');

        $response = $client->getResponse();
        if ($response->getStatusCode() == 500) {
            file_put_contents('/tmp/nsApiRotaPatchLab.log', $response->getContent());
        }

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('adequate', $decoded);
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
        if ($response->getStatusCode() == 500) {
            file_put_contents('/tmp/nsApiRotaGetRRL.log', $response->getContent());
        }

        $this->assertJsonResponse($response, 200);
        json_decode($response->getContent(), true);
    }

    public function testPatchRRLCase()
    {
        $route  = $this->getRoute('nsApiRotaPatchRRL');
        $client = $this->getClient();
        $client->request('PATCH', $route, [], [], [], '{"reference_lab":{"labId":"ANewCaseId"}}');

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
        $client->request('PUT', $route, [], [], [], '{"reference_lab":{"labId":"ANewCaseId"}}');

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
        $route  = $this->getUrl('nsApiRotaGetNL', ['objId' => self::ID]);
        $client = $this->getClient();
        $client->request('GET', $route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('status', $decoded);
    }

    public function testPatchNLCase()
    {
        $route  = $this->getRoute('nsApiRotaPatchNL');
        $client = $this->getClient();
        $client->request('PATCH', $route, [], [], [], '{"national_lab":{"labId":"ANewCaseId"}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $this->getRoute('nsApiRotaGetNL'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('lab_id', $decoded, print_r(array_keys($decoded), true));
        $this->assertEquals("ANewCaseId", $decoded['lab_id']);
    }

    public function testPutNLCase()
    {
        $route  = $this->getRoute('nsApiRotaPutNL');
        $client = $this->getClient();
        $client->request('PUT', $route, [], [], [], '{"national_lab":{"labId":"ANewCaseId"}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $this->getRoute('nsApiRotaGetNL'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('lab_id', $decoded);
        $this->assertEquals("ANewCaseId", $decoded['lab_id']);
    }

    public function testPutCase()
    {
        $route  = $this->getUrl('nsApiRotaPutCase', ['objId' => self::ID]);
        $client = $this->getClient();
        $client->request('PUT', $route, [], [], [], '{"case":{"lastName":"Fabien","caseId":"122"}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus')->find(self::ID);
        $this->assertEquals("Fabien", $case->getLastName(), "Change has occurred");
        $this->assertEquals(ArrayChoice::NO_SELECTION, $case->getGender()->getValue());
    }

    public function testPutCaseWithoutCaseId()
    {
        $route  = $this->getUrl('nsApiRotaPutCase', ['objId' => self::ID]);
        $client = $this->getClient();
        $client->request('PUT', $route, [], [], [], '{"case":{"lastName":"Fabien"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 400);
    }

    private function getRoute($route = 'nsApiRotaGetCase', $id = null)
    {
        $objId = $id === null ? self::ID : $id;

        return $this->getUrl($route, ['objId' => $objId]);
    }
}
