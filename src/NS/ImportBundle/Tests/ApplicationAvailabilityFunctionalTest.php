<?php

namespace NS\ImportBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Description of SmokeTest
 *
 * @author gnat
 */
class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     * @param $url
     */
    public function testPageIsSuccessful($url)
    {
        $client = $this->getClient();
        $client->followRedirects();
        $client->request('GET', $url);
        if(!$client->getResponse()->isSuccessful()) {
          file_put_contents(sprintf('/tmp/%s.log',str_replace('/','-',$url)),$client->getResponse()->getContent());
        }

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return [
            ['/en/import'],
            ['/en/export'],
            // ...
        ];
    }

    private function getClient()
    {
        $client    = self::createClient();
        $container = $client->getContainer();
        $user      =  $container->get('doctrine.orm.entity_manager')
            ->createQuery("SELECT u,a,l FROM NS\SentinelBundle\Entity\User u LEFT JOIN u.acls a LEFT JOIN u.referenceLab l WHERE u.email = :email")
            ->setParameter('email', 'ca-full@noblet.ca')
            ->getSingleResult();

        $session  = $container->get('session');
        $firewall = 'main_app';

        $this->assertNotEmpty($user->getRoles());
        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());

        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
        $client->followRedirects();

        return $client;
    }
}
