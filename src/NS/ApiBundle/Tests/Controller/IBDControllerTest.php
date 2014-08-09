<?php

namespace NS\ApiBundle\Tests\Controller;

use NS\ApiBundle\Tests\WebTestCase;

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
            'NS\SentinelBundle\DataFixtures\ORM\LoadMeningitisCaseData',
            'NS\ApiBundle\DataFixtures\ORM\LoadApiClientData',
        );

        $this->loadFixtures($classes);

        $user   = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy(array('email'=>'ca-api@noblet.ca'));
        $route  = $this->getUrl('ns_api_ibd_getibdcase',array('id'=>'CA-ALBCHLD-14-000001'));

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
        $route  = $this->getUrl('ns_api_ibd_patchibdcases',array('id'=>$id));

        $client = $this->createApiClient($user);
        $client->request('PATCH',$route,array(),array(),array(),'{"ibd":{"lastName":"Fabien"}}');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 202);
        $this->assertTrue($response->headers->has('Location'), "We have a location header");

        $gRoute = $this->getUrl('ns_api_ibd_getibdcase',array('id'=>$id));
        $this->assertEquals($response->headers->get('Location'), $gRoute, "The location matches the expected response");

        $case = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:Meningitis')->find($id);
        $this->assertEquals("Fabien",$case->getLastName(),"Change has occurred");
    }

    public function testPostCase()
    {
        $user   = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:User')->findOneBy(array('email'=>'ca-api@noblet.ca'));
        $route  = $this->getUrl('ns_api_ibd_postibdcases');

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
        $route  = $this->getUrl('ns_api_ibd_patchibdlab',array('id'=>$id));

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
}
