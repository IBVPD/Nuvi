<?php

namespace NS\SentinelBundle\Tests;
use \Liip\FunctionalTestBundle\Test\WebTestCase;

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