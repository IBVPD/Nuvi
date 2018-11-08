<?php

namespace NS\ApiBundle\Tests\Controller;

use NS\ApiBundle\Tests\WebTestCase;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Form\Types\Gender;
use NS\UtilBundle\Form\Types\ArrayChoice;

class PneumoniaControllerTest extends WebTestCase
{
    const ID = 'CA-ALBCHLD-15-000001';

    public function setUp()
    {
        parent::setUp();
    }

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
        $route  = $this->getRoute('nsApiPneumoniaPatchCase');
        $client = $this->getClient();
        $client->request('PATCH', $route, [], [], [], '{"case":{"lastName":"Fabien","gender":"1"}}');

        $response = $client->getResponse();
        if($response->getStatusCode() !== 204) {
            file_put_contents('/tmp/patchPneumonia.log', sprintf("%s\n\n%s",self::ID, $response->getContent()));
        }

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), 'We have a location header');

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Pneumonia::class)->find(self::ID);
        $this->assertEquals("Fabien", $case->getLastName(), 'Change has occurred');
        $this->assertTrue($case->getGender()->equal(Gender::MALE));
    }

    public function testPostCase()
    {
        $route  = $this->getUrl('nsApiPneumoniaPostCase');
        $client = $this->getClient();
        $client->request('POST', $route, [], [], [], '{"create":{"caseId":"ANewCaseId","type":1,"site":"ALBCHLD"}}');

        $response = $client->getResponse();
        if($response->getStatusCode() !== 204) {
            file_put_contents('/tmp/postPneumonia.log', sprintf("%s\n\n%s",self::ID, $response->getContent()));
        }

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->headers->has('Location'), 'We have a location header');

        $client->request('GET', $response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('case_id', $decoded);
        $this->assertArrayNotHasKey('lab', $decoded);
        $this->assertEquals("ANewCaseId", $decoded['case_id']);
    }

    /**
     * @group lab
     */
    public function testLabCase()
    {
        $route  = $this->getUrl('nsApiPneumoniaPatchLab', ['objId' => self::ID]);
        $client = $this->getClient();
        $client->request('PATCH', $route, [], [], [], '{"site_lab":{"bloodId":"ANewCaseId","bloodCultDone":0}}');

        $response = $client->getResponse();
        if (!$response->isSuccessful()) {
            file_put_contents('/tmp/pneumonia-lab.html', $response);
        }

        $this->assertEquals(204, $response->getStatusCode(), $route);
        $this->assertFalse($response->headers->has('Location'), 'We have a location header');

        $client->request('GET', $this->getRoute('nsApiPneumoniaGetLab'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('blood_id', $decoded);
        $this->assertEquals('ANewCaseId', $decoded['blood_id']);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function testGetRRLCase()
    {
        $client = $this->getClient();
        $client->request('GET', $this->getRoute('nsApiPneumoniaGetRRL'));

        $response = $client->getResponse();
        if ($response->getStatusCode() === 500) {
            file_put_contents('/tmp/nsApiPneumoniaGetRRL.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        json_decode($response->getContent(), true);
    }

    public function testPatchRRLCase()
    {
        $route  = $this->getRoute('nsApiPneumoniaPatchRRL');
        $client = $this->getClient();
        $client->request('PATCH', $route, [], [], [], '{"reference_lab":{"labId":"ANewCaseId","sampleType":1}}');

        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), 'We have a location header');

// Because our user can't patch the RRL, no data is set/changed.
//        $client->request('GET', $this->getRoute('nsApiPneumoniaGetRRL'));
//        $response = $client->getResponse();
//        $this->assertJsonResponse($response, 200);
//        $decoded  = json_decode($response->getContent(), true);
//
//        $this->assertArrayHasKey('LabId', $decoded, print_r(array_keys($decoded), true));
//        $this->assertEquals("ANewCaseId", $decoded['LabId']);
    }

    public function testPutRRLCase()
    {
        $route  = $this->getRoute('nsApiPneumoniaPutRRL');
        $client = $this->getClient();
        $client->request('PUT', $route, [], [], [], '{"reference_lab":{"labId":"ANewCaseId","sampleType":2}}');

        $response = $client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), 'We have a location header');

// Because our user can't patch the RRL, no data is set/changed.
//        $client->request('GET', $this->getRoute('nsApiPneumoniaGetRRL'));
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
        $route  = $this->getRoute('nsApiPneumoniaGetNL');
        $client = $this->getClient();
        $client->request('GET', $route);

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('status', $decoded);
    }

    public function testPatchNLCase()
    {
        $route  = $this->getRoute('nsApiPneumoniaPatchNL');
        $client = $this->getClient();
        $client->request('PATCH', $route, [], [], [], '{"national_lab":{"labId":"ANewCaseId","sampleType":1}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode(),$response->getContent());
        $this->assertFalse($response->headers->has('Location'), 'We have a location header');

        $client->request('GET', $this->getRoute('nsApiPneumoniaGetNL'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('lab_id', $decoded,print_r($decoded,true));
        $this->assertEquals("ANewCaseId", $decoded['lab_id']);
    }

    public function testPutNLCase()
    {
        $route  = $this->getRoute('nsApiPneumoniaPutNL');
        $client = $this->getClient();
        $client->request('PUT', $route, [], [], [], '{"national_lab":{"labId":"ANewCaseId","sampleType":2}}');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), 'We have a location header');

        $client->request('GET', $this->getRoute('nsApiPneumoniaGetNL'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $decoded  = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('lab_id', $decoded);
        $this->assertEquals("ANewCaseId", $decoded['lab_id']);
        $this->assertArrayHasKey('type_sample_recd', $decoded,print_r($decoded,true));
    }

    public function testPutCase()
    {
        $route  = $this->getRoute('nsApiPneumoniaPutCase');
        $client = $this->getClient();
        $client->request('PUT', $route, [], [], [], '{"case":{"lastName":"Fabien","caseId":"123"}}');

        $response = $client->getResponse();
        if (!$response->isSuccessful()) {
            file_put_contents('/tmp/pneu-put-case.html', $response);
        }

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($response->headers->has('Location'), 'We have a location header');

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Pneumonia::class)->find(self::ID);
        $this->assertEquals("Fabien", $case->getLastName(), 'Change has occurred');
        $this->assertEquals(ArrayChoice::NO_SELECTION, $case->getGender()->getValue());
    }

    public function testPutCaseWithoutCaseId()
    {
        $route  = $this->getRoute('nsApiPneumoniaPutCase');
        $client = $this->getClient();
        $client->request('PUT', $route, [], [], [], '{"case":{"lastName":"Fabien"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 400);
    }

    protected function getRoute($route = 'nsApiPneumoniaGetCase', $id = null)
    {
        $objId = $id === null ? self::ID : $id;

        return $this->getUrl($route, ['objId' => $objId]);
    }
}
