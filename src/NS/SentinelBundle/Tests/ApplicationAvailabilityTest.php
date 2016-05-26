<?php

namespace NS\SentinelBundle\Tests;

use \Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use \Symfony\Component\BrowserKit\Cookie;
use \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ApplicationAvailabilityTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     * @param $url
     */
    public function testPageIsSuccessful($url)
    {
        $client = $this->getClient('ca-full@noblet.ca');
        $client->followRedirects();
        $client->request('GET', $url);

        if (!$client->getResponse()->isSuccessful()) {
            file_put_contents(sprintf('/tmp/%s.log', str_replace('/', '-', $url)), $client->getResponse()->getContent());
        }

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return array(
            array('/en/ibd'),
            array('/en/rota'),
            array('/en/ibd/reports/data-quality'),
            array('/en/ibd/reports/site-performance'),
            array('/en/ibd/reports/data-linking'),
            array('/en/ibd/reports/annual-age-distribution'),
            array('/en/ibd/reports/percent-enrolled'),
            array('/en/ibd/reports/field-population'),
            array('/en/ibd/reports/culture-positive'),
            array('/en/rota/reports/data-quality'),
            array('/en/rota/reports/site-performance'),
            array('/en/rota/reports/data-linking'),
            array('/en/profile'),
            array('/en/zero-report'),
        );
    }

    /**
     * @param $url
     * @param string $button
     * @param array $params
     *
     * @dataProvider getFormUrls
     * @group form
     */
    public function testFormSubmission($url, $button, array $params)
    {
        $client = $this->getClient('ca-full@noblet.ca');
        $client->followRedirects();
        $crawler = $client->request('GET', $url);

        $form = $crawler->selectButton($button)->form();
        foreach ($params as $key => $value) {
            $form[$key] = $value;
        }

        $client->submit($form);

        if (!$client->getResponse()->isSuccessful()) {
            file_put_contents(sprintf('/tmp/%s-form.log', str_replace('/', '-', $url)), $client->getResponse()->getContent());
        }
    }

    public function getFormUrls()
    {
        return array(
//            array('/en/ibd','ibd_filter_form[find]',array('ibd_filter_form[id]'=>'123')),
//            array('/en/rota'),
            array('/en/ibd/reports/data-quality','report_filter[filter]', array('report_filter[adm_date][left_date]'=>'01/01/2014','report_filter[adm_date][right_date]'=>'12/31/2014')),
            array('/en/ibd/reports/site-performance','base_quarterly_filter[filter]', array('base_quarterly_filter[year]'=>2014)),
            array('/en/ibd/reports/data-linking','quarterly_linking_report_filter[filter]', array('quarterly_linking_report_filter[year]'=>2014)),
            array('/en/ibd/reports/annual-age-distribution','report_filter[filter]', array('report_filter[adm_date][left_date]'=>'01/01/2014','report_filter[adm_date][right_date]'=>'12/31/2014')),
            array('/en/ibd/reports/percent-enrolled','report_filter[filter]', array('report_filter[adm_date][left_date]'=>'01/01/2014','report_filter[adm_date][right_date]'=>'12/31/2014')),
            array('/en/ibd/reports/field-population','report_filter[filter]', array('report_filter[adm_date][left_date]'=>'01/01/2014','report_filter[adm_date][right_date]'=>'12/31/2014')),
            array('/en/ibd/reports/culture-positive','report_filter[filter]', array('report_filter[adm_date][left_date]'=>'01/01/2014','report_filter[adm_date][right_date]'=>'12/31/2014')),
            array('/en/rota/reports/data-quality','report_filter[filter]', array('report_filter[adm_date][left_date]'=>'01/01/2014','report_filter[adm_date][right_date]'=>'12/31/2014')),
            array('/en/rota/reports/site-performance','base_quarterly_filter[filter]', array('base_quarterly_filter[year]'=>2014)),
            array('/en/rota/reports/data-linking','quarterly_linking_report_filter[filter]', array('quarterly_linking_report_filter[year]'=>2014)),
//            array('/en/profile','submit',array('ns_sentinelbundle_user[name]'=>'Test User Name')),
        );
    }

    /**
     * @param string $email
     * @param string $url
     * @param int $expectedResponseCode
     *
     * @dataProvider getAdminUrls
     * @group adminUrls
     */
    public function testAdminUrls($email, $url, $expectedResponseCode)
    {
        $client = $this->getClient($email);
        $client->followRedirects();
        $client->request('GET', $url);

        $statusCode = $client->getResponse()->getStatusCode();
        if ($statusCode !== $expectedResponseCode) {
            file_put_contents(sprintf('/tmp/%s.log', str_replace('/', '-', $url)), $client->getResponse()->getContent());
        }

        $this->assertEquals($expectedResponseCode, $statusCode);
    }

    public function getAdminUrls()
    {
        $super   = 'superadmin@noblet.ca';
        $region  = 'na@noblet.ca';
        $country = 'ca@noblet.ca';

        return array(
            // Super
            array($super, '/en/admin/dashboard', 200),
            array($super, '/en/admin/ns/sentinel/region/create', 200),
            array($super, '/en/admin/ns/sentinel/region/NA/show', 200),
//            array($super, '/en/admin/ns/sentinel/region/NA/delete', 403),
            array($super, '/en/admin/ns/sentinel/region/list', 200),
            array($super, '/en/admin/ns/sentinel/country/create', 200),
            array($super, '/en/admin/ns/sentinel/country/list', 200),
            array($super, '/en/admin/ns/sentinel/country/CA/show', 200),
//            array($super, '/en/admin/ns/sentinel/country/CA/delete', 403),
            array($super, '/en/admin/ns/sentinel/site/create', 200),
            array($super, '/en/admin/ns/sentinel/site/list', 200),
            array($super, '/en/admin/ns/sentinel/site/ALBCHLD/show', 200),
//            array($super, '/en/admin/ns/sentinel/site/ALBCHLD/delete', 403),
            array($super, '/en/admin/ns/sentinel/referencelab/create', 200),
            array($super, '/en/admin/ns/sentinel/referencelab/list', 200),
            array($super, '/en/admin/ns/sentinel/acl/create', 200),
            array($super, '/en/admin/ns/sentinel/user/create', 200),
            array($super, '/en/admin/ns/sentinel/user/list', 200),
            array($super, '/en/admin/ns/api/client/create', 200),
            array($super, '/en/admin/ns/api/client/list', 200),
            array($super, '/en/admin/ns/api/remote/create', 200),
            array($super, '/en/admin/ns/api/remote/list', 200),
            array($super, '/en/admin/ns/import/map/create', 200),
            array($super, '/en/admin/ns/import/map/list', 200),

            // Region
            array($region, '/en/admin/dashboard', 200),
            array($region, '/en/admin/ns/sentinel/region/create', 200),
            array($region, '/en/admin/ns/sentinel/region/list', 200),
            array($region, '/en/admin/ns/sentinel/region/NA/show', 200),
            array($region, '/en/admin/ns/sentinel/region/NA/delete', 403),
            array($region, '/en/admin/ns/sentinel/country/create', 200),
            array($region, '/en/admin/ns/sentinel/country/list', 200),
            array($region, '/en/admin/ns/sentinel/country/CA/show', 200),
            array($region, '/en/admin/ns/sentinel/country/CA/delete', 403),
            array($region, '/en/admin/ns/sentinel/site/create', 200),
            array($region, '/en/admin/ns/sentinel/site/list', 200),
            array($region, '/en/admin/ns/sentinel/site/ALBCHLD/show', 200),
            array($region, '/en/admin/ns/sentinel/site/ALBCHLD/delete', 403),
            array($region, '/en/admin/ns/sentinel/referencelab/create', 200),
            array($region, '/en/admin/ns/sentinel/referencelab/list', 200),
            array($region, '/en/admin/ns/sentinel/acl/create', 200),
            array($region, '/en/admin/ns/sentinel/user/create', 200),
            array($region, '/en/admin/ns/sentinel/user/list', 200),
            array($region, '/en/admin/ns/api/client/create', 403),
            array($region, '/en/admin/ns/api/client/list', 403),
            array($region, '/en/admin/ns/api/remote/create', 403),
            array($region, '/en/admin/ns/api/remote/list', 403),
            array($region, '/en/admin/ns/import/map/create', 200),
            array($region, '/en/admin/ns/import/map/list', 200),

            //Country
            array($country, '/en/admin/dashboard', 200),
            array($country, '/en/admin/ns/sentinel/region/create', 403),
            array($country, '/en/admin/ns/sentinel/region/list', 403),
            array($country, '/en/admin/ns/sentinel/region/NA/show', 403),
            array($country, '/en/admin/ns/sentinel/region/NA/delete', 403),
            array($country, '/en/admin/ns/sentinel/country/create', 403),
            array($country, '/en/admin/ns/sentinel/country/list', 200),
            array($country, '/en/admin/ns/sentinel/country/CA/show', 200),
            array($country, '/en/admin/ns/sentinel/country/CA/delete', 403),
            array($country, '/en/admin/ns/sentinel/site/create', 200),
            array($country, '/en/admin/ns/sentinel/site/list', 200),
            array($country, '/en/admin/ns/sentinel/site/ALBCHLD/show', 200),
            array($country, '/en/admin/ns/sentinel/site/ALBCHLD/delete', 403),
            array($country, '/en/admin/ns/sentinel/referencelab/create', 403),
            array($country, '/en/admin/ns/sentinel/referencelab/list', 403),
            array($country, '/en/admin/ns/sentinel/acl/create', 200),
            array($country, '/en/admin/ns/sentinel/user/create', 200),
            array($country, '/en/admin/ns/sentinel/user/list', 200),
            array($country, '/en/admin/ns/api/client/create', 403),
            array($country, '/en/admin/ns/api/client/list', 403),
            array($country, '/en/admin/ns/api/remote/create', 403),
            array($country, '/en/admin/ns/api/remote/list', 403),
            array($country, '/en/admin/ns/import/map/create', 403),
            array($country, '/en/admin/ns/import/map/list', 403),
        );
    }

    private function getClient($email)
    {
        $client    = self::createClient();
        $container = $client->getContainer();
        $user      =  $container->get('doctrine.orm.entity_manager')
            ->createQuery("SELECT u,a,l FROM NS\SentinelBundle\Entity\User u LEFT JOIN u.acls a LEFT JOIN u.referenceLab l WHERE u.email = :email")
            ->setParameter('email', $email)
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
