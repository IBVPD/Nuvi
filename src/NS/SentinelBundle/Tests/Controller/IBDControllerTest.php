<?php

namespace NS\SentinelBundle\Tests\Controller;

use \NS\SentinelBundle\Tests\BaseWebTestCase;

/**
 * Description of IBDControllerTest
 *
 * @author gnat
 */
class IBDControllerTest extends BaseWebTestCase
{
    const ID = 'CA-ALBCHLD-15-000001';

    public function testIbdEdit()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() == 500) {
            file_put_contents('/tmp/ibdEdit.log', sprintf("%s%s\n%s",'/en/ibd/edit/',self::ID, $response->getContent()));
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdIndex()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/');
        $response = $client->getResponse();
        if ($response->getStatusCode() == 500) {
            file_put_contents('/tmp/ibdIndex.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdShow()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/show/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() == 500) {
            file_put_contents('/tmp/ibdShow.log', $response->getContent());
        }
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdLab()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/lab/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() == 500) {
            file_put_contents('/tmp/ibdLabEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdRRL()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/rrl/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() == 500) {
            file_put_contents('/tmp/ibdRRLEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdNL()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/nl/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() == 500) {
            file_put_contents('/tmp/ibdNlEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdOutcome()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/outcome/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() == 500) {
            file_put_contents('/tmp/ibdOutcomeEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }
}
