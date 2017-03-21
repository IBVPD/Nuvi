<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 18/05/16
 * Time: 11:30 AM
 */

namespace NS\SentinelBundle\Tests\Admin;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserAdminTest extends WebTestCase
{
    private $url = '/en/admin/ns/sentinel/user/list';

    /**
     * @param string $email
     * @param int $expectedUserCount
     * @dataProvider getAdmins
     */
    public function testAdmins($email, $expectedUserCount)
    {
        $client = $this->getClient($email);
        $client->followRedirects();
        $crawler = $client->request('GET', $this->url);

        if (!$client->getResponse()->isSuccessful()) {
            file_put_contents(sprintf('/tmp/%s.log', str_replace('/', '-', $this->url)), $client->getResponse()->getContent());
        }
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals($expectedUserCount, $crawler->filter('table.table-bordered tbody:first-of-type tr')->count());
    }

    public function getAdmins()
    {
        return [
            ['superadmin@noblet.ca', 17],
            ['na@noblet.ca', 15],
            ['ca@noblet.ca', 11]
        ];
    }

    private function getClient($email)
    {
        $client = self::createClient();
        $container = $client->getContainer();
        $user = $container->get('doctrine.orm.entity_manager')
            ->createQuery("SELECT u,a,l FROM NS\SentinelBundle\Entity\User u LEFT JOIN u.acls a LEFT JOIN u.referenceLab l WHERE u.email = :email")
            ->setParameter('email', $email)
            ->getSingleResult();

        $session = $container->get('session');
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
