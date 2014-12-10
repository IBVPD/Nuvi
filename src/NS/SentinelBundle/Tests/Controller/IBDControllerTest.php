<?php

namespace NS\SentinelBundle\Tests\Controller;

use \Liip\FunctionalTestBundle\Test\WebTestCase;
use \Symfony\Component\BrowserKit\Cookie;

/**
 * Description of IBDControllerTest
 *
 * @author gnat
 */
class IBDControllerTest extends WebTestCase
{

//Scenario Outline: All IBD forms
//    Given I am not logged in
//      And I login with "<email>" "<password>"
//      And I visit "<path>" with "<id>"
//    Then There should be no exception
//    Examples:
//      | email             | password          | path                  | id                   |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/outcome/edit  | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/edit/        | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/lab/edit     | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/rrl/edit     | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/nl/edit      | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/outcome/edit | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/show         | CA-ALBCHLD-%d-000001 |

    const ID = 'CA-ALBCHLD-14-000001';

    public function testIbdEdit()
    {
        // add all your doctrine fixtures classes
        $classes = array(
            // classes implementing Doctrine\Common\DataFixtures\FixtureInterface
            'NS\SentinelBundle\DataFixtures\ORM\LoadUserData',
            'NS\SentinelBundle\DataFixtures\ORM\LoadRegionData',
            'NS\SentinelBundle\DataFixtures\ORM\LoadIBDCaseData',
        );
        $this->loadFixtures($classes);

        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/edit/' . self::ID);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdIndex()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdShow()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/show/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdLab()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/lab/edit/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdRRL()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/rrl/edit/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdNL()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/nl/edit/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testIbdOutcome()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/outcome/edit/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testRotaEdit()
    {
        // add all your doctrine fixtures classes
        $classes = array(
            // classes implementing Doctrine\Common\DataFixtures\FixtureInterface
            'NS\SentinelBundle\DataFixtures\ORM\LoadUserData',
            'NS\SentinelBundle\DataFixtures\ORM\LoadRegionData',
            'NS\SentinelBundle\DataFixtures\ORM\LoadRotaVirusCaseData',
        );
        $this->loadFixtures($classes);

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

    private function getRoute($route = '')
    {
        return $this->getUrl($route, array('id' => self::ID, '_locale' => 'en'));
    }

    private function login()
    {
        $user = $this->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('NSSentinelBundle:User')
            ->findOneByEmail(array('email' => 'ca-full@noblet.ca'));

        $this->loginAs($user, 'main_app');
        $client = $this->makeClient();
        $client->followRedirects();
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        return $client;
    }

}
