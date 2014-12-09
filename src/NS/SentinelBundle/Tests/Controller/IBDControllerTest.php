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
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/edit/         | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/lab/edit/     | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/rrl/edit/     | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/nl/edit       | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/outcome/edit  | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/ibd/show          | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/edit/        | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/lab/edit     | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/rrl/edit     | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/nl/edit      | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/outcome/edit | CA-ALBCHLD-%d-000001 |
//      | ca-full@noblet.ca | 1234567-ca-full   | /en/rota/show         | CA-ALBCHLD-%d-000001 |

    const ID = 'CA-ALBCHLD-14-000001';

    public function testEdit()
    {
        // add all your doctrine fixtures classes
        $classes = array(
            // classes implementing Doctrine\Common\DataFixtures\FixtureInterface
            'NS\SentinelBundle\DataFixtures\ORM\LoadUserData',
            'NS\SentinelBundle\DataFixtures\ORM\LoadRegionData',
            'NS\SentinelBundle\DataFixtures\ORM\LoadIBDCaseData',
        );
        $this->loadFixtures($classes);

        $client = $this->login();

        $client->request('GET', $this->getRoute('ibdEdit'));
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testShow()
    {
        $client   = $this->login();
        $crawler  = $client->request('GET', '/en/ibd/show/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.blockException')->count());
    }

    public function testLab()
    {
        $client = $this->login();

        $client->request('GET', '/en/ibd/lab/edit/' . self::ID);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    private function getRoute($route = '')
    {
        return $this->getUrl($route, array('id' => self::ID, '_locale' => 'en'));
    }

    private function login()
    {
        $userParams = array('_username' => 'ca-full@noblet.ca', '_password' => '1234567-ca-full');

        $client = $this->createClient();
        $client->followRedirects();
        $client->request('PUT', '/login_check', $userParams);

        $cookie = new Cookie('locale2', 'en', time() + 3600 * 24 * 7, '/', null, false, false);
        $client->getCookieJar()->set($cookie);

        $this->assertEquals($client->getResponse()->getStatusCode(), 200);

        return $client;
    }

}
