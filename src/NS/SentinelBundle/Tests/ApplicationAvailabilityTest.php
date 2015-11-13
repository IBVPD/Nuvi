<?php

namespace NS\SentinelBundle\Tests;

use \Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use \Symfony\Component\BrowserKit\Cookie;
use \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class ApplicationAvailabilityTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = $this->getClient();
        $client->followRedirects();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful(),$client->getResponse());
    }

    public function urlProvider()
    {
        return array(
            array('/en/ibd'),
            array('/en/rota'),
            array('/en/ibd/reports/data-quality'),
            array('/en/ibd/reports/site-performance'),
            array('/en/ibd/reports/annual-age-distribution'),
            array('/en/ibd/reports/percent-enrolled'),
            array('/en/ibd/reports/field-population'),
            array('/en/ibd/reports/culture-positive'),
            array('/en/rota/reports/data-quality'),
            array('/en/rota/reports/site-performance'),
            array('/en/profile'),
        );
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
