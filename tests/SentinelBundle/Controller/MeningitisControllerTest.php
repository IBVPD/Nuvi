<?php

namespace NS\SentinelBundle\Tests\Controller;

use NS\SentinelBundle\Tests\BaseWebTestCase;

/**
 * Description of IBDControllerTest
 *
 * @author gnat
 */
class MeningitisControllerTest extends BaseWebTestCase
{
    const ID = 'CA-ALBCHLD-15-000001';

    public function testIbdEdit()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/meningitis/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() !== 200) {
            file_put_contents('/tmp/meningitisEdit.log', sprintf("%s%s\n%s",'/en/meningitis/edit/',self::ID, $response->getContent()));
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdIndex()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/meningitis/');
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/meningitisIndex.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdShow()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/meningitis/show/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/meningitisShow.log', $response->getContent());
        }
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdLab()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/meningitis/lab/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/meningitisLabEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdRRL()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/meningitis/rrl/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/meningitisRRLEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdNL()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/meningitis/nl/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/meningitisNlEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdOutcome()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/meningitis/outcome/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/meningitisOutcomeEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }
}
