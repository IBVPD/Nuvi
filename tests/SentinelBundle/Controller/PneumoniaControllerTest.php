<?php

namespace NS\SentinelBundle\Tests\Controller;

use NS\SentinelBundle\Tests\BaseWebTestCase;

/**
 * Description of IBDControllerTest
 *
 * @author gnat
 */
class PneumoniaControllerTest extends BaseWebTestCase
{
    const ID = 'CA-ALBCHLD-15-000001';

    public function testIbdEdit()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/pneumonia/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() !== 200) {
            file_put_contents('/tmp/pneumoniaEdit.log', sprintf("%s%s\n%s",'/en/pneumonia/edit/',self::ID, $response->getContent()));
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdIndex()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/pneumonia/');
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/pneumoniaIndex.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdShow()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/pneumonia/show/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/pneumoniaShow.log', $response->getContent());
        }
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdLab()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/pneumonia/lab/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/pneumoniaLabEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdRRL()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/pneumonia/rrl/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/pneumoniaRRLEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdNL()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/pneumonia/nl/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/pneumoniaNlEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdOutcome()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/pneumonia/outcome/edit/' . self::ID);
        $response = $client->getResponse();
        if ($response->getStatusCode() != 200) {
            file_put_contents('/tmp/pneumoniaOutcomeEdit.log', $response->getContent());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }
}
