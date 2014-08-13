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

        $user   = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy(array('email'=>'ca-api@noblet.ca'));
        $route  = $this->getUrl('nsApiIbdGetCase',array('id'=>'CA-ALBCHLD-14-000001'));

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
        $id     = 'CA-ALBCHLD-14-000001';
        $user   = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy(array('email'=>'ca-api@noblet.ca'));
        $route  = $this->getUrl('nsApiIbdPatchCase',array('id'=>$id));

        $client = $this->createApiClient($user);
        $client->request('PATCH',$route,array(),array(),array(),'{"ibd":{"lastName":"Fabien"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $gRoute = $this->getUrl('nsApiIbdGetCase',array('id'=>$id));
        $this->assertEquals($response->headers->get('Location'), $gRoute, "The location matches the expected response");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD')->find($id);
        $this->assertEquals("Fabien",$case->getLastName(),"Change has occurred");
        $this->assertTrue($case->getGender()->equal(\NS\SentinelBundle\Form\Types\Gender::MALE));
    }

    public function testPostCase()
    {
        $user   = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy(array('email'=>'ca-api@noblet.ca'));
        $route  = $this->getUrl('nsApiIbdPostCase');

        $client = $this->createApiClient($user);
        $client->request('POST',$route,array(),array(),array(),'{"create_ibd":{"caseId":"ANewCaseId","type":1,"site":"ALBCHLD"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 201);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $client->request('GET',$response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response,200);
        $decoded = json_decode($response->getContent(),true);

        $this->assertArrayHasKey('CaseId', $decoded);
        $this->assertArrayNotHasKey('Lab', $decoded);
        $this->assertEquals("ANewCaseId",$decoded['CaseId']);
    }

    public function testLabCase()
    {
        $id     = 'CA-ALBCHLD-14-000001';
        $user   = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy(array('email'=>'ca-api@noblet.ca'));
        $route  = $this->getUrl('nsApiIbdPatchLab',array('id'=>$id));

        $client = $this->createApiClient($user);
        $client->request('PATCH',$route,array(),array(),array(),'{"ibd_lab":{"csfSiteId":"ANewCaseId","csfGramDone":0,"csfCultDone":0}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $client->request('GET',$response->headers->get('Location'));
        $response = $client->getResponse();
        $this->assertJsonResponse($response,200);
        $decoded = json_decode($response->getContent(),true);

        $this->assertArrayHasKey('CsfSiteId', $decoded);
        $this->assertEquals("ANewCaseId",$decoded['CsfSiteId']);
    }

    public function testPutCase()
    {
        $id     = 'CA-ALBCHLD-14-000001';
        $user   = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy(array('email'=>'ca-api@noblet.ca'));
        $route  = $this->getUrl('nsApiIbdPutCase',array('id'=>$id));

        $client = $this->createApiClient($user);
        $client->request('PUT',$route,array(),array(),array(),'{"ibd":{"lastName":"Fabien","caseId":"12"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $gRoute = $this->getUrl('nsApiIbdGetCase',array('id'=>$id));
        $this->assertEquals($response->headers->get('Location'), $gRoute, "The location matches the expected response");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD')->find($id);
        $this->assertEquals("Fabien",$case->getLastName(),"Change has occurred");
        $this->assertEquals(ArrayChoice::NO_SELECTION, $case->getGender()->getValue());
    }

    public function testPutCaseWithoutCaseId()
    {
        $id     = 'CA-ALBCHLD-14-000001';
        $user   = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy(array('email'=>'ca-api@noblet.ca'));
        $route  = $this->getUrl('nsApiIbdPutCase',array('id'=>$id));

        $client = $this->createApiClient($user);
        $client->request('PUT',$route,array(),array(),array(),'{"rotavirus":{"lastName":"Fabien"');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 400);
    }
}
