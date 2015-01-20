<?php

namespace NS\SentinelBundle\Tests\Controller;

use \NS\SentinelBundle\Tests\BaseWebTestCase;

/**
 * Description of RotaVirusControllerTest
 *
 * @author gnat
 */
class RotaVirusControllerTest extends BaseWebTestCase
{
    const ID = 'CA-ALBCHLD-15-000081';

    public function testRotaEdit()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/rota/edit/' . self::ID);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testRotaIndex()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/rota/');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testRotaShow()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/rota/show/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testRotaLab()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/rota/lab/edit/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testRotaRRL()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/rota/rrl/edit/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testRotaNL()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/rota/nl/edit/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testRotaOutcome()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/rota/outcome/edit/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }
}