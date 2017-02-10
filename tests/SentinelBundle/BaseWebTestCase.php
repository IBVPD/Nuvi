<?php

namespace NS\SentinelBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use NS\SentinelBundle\Entity\User;

/**
 * Description of BaseWebTestCase
 *
 * @author gnat
 */
class BaseWebTestCase extends WebTestCase
{
    protected function login()
    {
        $user = $this->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->createQuery('SELECT u,a,r FROM NSSentinelBundle:User u LEFT JOIN u.acls a LEFT JOIN u.referenceLab r WHERE u.email = :email')
            ->setParameter('email', 'ca-full@noblet.ca')
            ->getSingleResult();

        $this->assertInstanceOf(User::class, $user);

        $this->loginAs($user, 'main_app');
        $client = $this->makeClient();
        $client->followRedirects();
        $client->request('GET', '/');

        if ($client->getResponse()->getStatusCode() !== 200) {
            file_put_contents('/tmp/login.err', $client->getResponse()->getContent());
        }

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        return $client;
    }
}
