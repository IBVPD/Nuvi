<?php

namespace NS\ApiBundle\Tests\Controller;

use \NS\ApiBundle\Tests\WebTestCase;
use \NS\SentinelBundle\Form\Types\Gender;
use \NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of IBDController
 *
 * @author gnat
 */
class IBDControllerTest extends WebTestCase
{
    const ID = 'CA-ALBCHLD-15-000001';

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
        $route  = $this->getRoute('nsApiIbdPatchCase');
        $client = $this->getClient();
        $client->request('PATCH', $route, array(), array(), array(), '{"ibd":{"lastName":"Fabien","gender":"1"}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD')->find(self::ID);
        $this->assertEquals("Fabien", $case->getLastName(), "Change has occurred");
        $this->assertTrue($case->getGender()->equal(Gender::MALE));
    }

    public function testPostCase()
    {
        $route  = $this->getUrl('nsApiIbdPostCase');
        $client = $this->getClient();
        $client->request('POST', $route, array(), array(), array(), '{"create_case":{"caseId":"ANewCaseId","type":1,"site":"ALBCHLD"}}');

        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
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
        $route  = $this->getUrl('nsApiIbdPatchLab', array('objId' => self::ID));
        $client = $this->getClient();
        $client->request('PATCH', $route, array(), array(), array(), '{"ibd_lab":{"csfId":"ANewCaseId","csfGramDone":0,"csfCultDone":0}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode(), $route);
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $this->getRoute('nsApiIbdGetLab'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('CsfId', $decoded, print_r($decoded, true));
        $this->assertEquals("ANewCaseId", $decoded['CsfId']);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function testGetRRLCase()
    {
        $client = $this->getClient();
        $client->request('GET', $this->getRoute('nsApiIbdGetRRL'));

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        json_decode($response->getContent(), true);
    }

    public function testPatchRRLCase()
    {
        $route  = $this->getRoute('nsApiIbdPatchRRL');
        $client = $this->getClient();
        $client->request('PATCH', $route, array(), array(), array(), '{"ibd_referencelab":{"labId":"ANewCaseId","sampleType":1}}');

        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

// Because our user can't patch the RRL, no data is set/changed.
//        $client->request('GET', $this->getRoute('nsApiIbdGetRRL'));
//        $response = $client->getResponse();
//        $this->assertJsonResponse($response, 200);
//        $decoded  = json_decode($response->getContent(), true);
//
//        $this->assertArrayHasKey('LabId', $decoded, print_r(array_keys($decoded), true));
//        $this->assertEquals("ANewCaseId", $decoded['LabId']);
    }

    public function testPutRRLCase()
    {
        $route  = $this->getRoute('nsApiIbdPutRRL');
        $client = $this->getClient();
        $client->request('PUT', $route, array(), array(), array(), '{"ibd_referencelab":{"labId":"ANewCaseId","sampleType":2}}');

        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

// Because our user can't patch the RRL, no data is set/changed.
//        $client->request('GET', $this->getRoute('nsApiIbdGetRRL'));
//        $response = $client->getResponse();
//        $this->assertJsonResponse($response, 200);
//        $decoded  = json_decode($response->getContent(), true);
//
//        $this->assertArrayHasKey('LabId', $decoded);
//        $this->assertEquals("ANewCaseId", $decoded['LabId']);
//        $this->assertArrayHasKey('SampleType', $decoded);
    }

    public function testGetNLCase()
    {
        $route  = $this->getRoute('nsApiIbdGetNL');
        $client = $this->getClient();
        $client->request('GET', $route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Status', $decoded, print_r($decoded, true));
    }

    public function testPatchNLCase()
    {
        $route  = $this->getRoute('nsApiIbdPatchNL');
        $client = $this->getClient();
        $client->request('PATCH', $route, array(), array(), array(), '{"ibd_nationallab":{"labId":"ANewCaseId","sampleType":1}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $this->getRoute('nsApiIbdGetNL'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('LabId', $decoded, print_r(array_keys($decoded), true));
        $this->assertEquals("ANewCaseId", $decoded['LabId']);
    }

    public function testPutNLCase()
    {
        $route  = $this->getRoute('nsApiIbdPutNL');
        $client = $this->getClient();
        $client->request('PUT', $route, array(), array(), array(), '{"ibd_nationallab":{"labId":"ANewCaseId","sampleType":2}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $client->request('GET', $this->getRoute('nsApiIbdGetNL'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('LabId', $decoded);
        $this->assertEquals("ANewCaseId", $decoded['LabId']);
        $this->assertArrayHasKey('SampleType', $decoded);
    }

    public function testPutCase()
    {
        $route  = $this->getRoute('nsApiIbdPutCase');
        $client = $this->getClient();
        $client->request('PUT', $route, array(), array(), array(), '{"ibd":{"lastName":"Fabien","caseId":"12"}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), "We have a location header");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD')->find(self::ID);
        $this->assertEquals("Fabien", $case->getLastName(), "Change has occurred");
        $this->assertEquals(ArrayChoice::NO_SELECTION, $case->getGender()->getValue());
    }

    public function testPutCaseWithoutCaseId()
    {
        $route  = $this->getRoute('nsApiIbdPutCase');
        $client = $this->getClient();
        $client->request('PUT', $route, array(), array(), array(), '{"rotavirus":{"lastName":"Fabien"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 400);
    }

    protected function getRoute($route = 'nsApiIbdGetCase', $id = null)
    {
        $objId = $id === null ? self::ID : $id;

        return $this->getUrl($route, array('objId' => $objId));
    }
}
