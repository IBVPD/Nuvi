<?php

namespace NS\SentinelBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ApplicationAvailabilityTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     * @param $url
     */
    public function testPageIsSuccessful($url): void
    {
        $client = $this->getClient('ca-full@noblet.ca');
        $client->followRedirects();
        $client->request('GET', $url);

        if (!$client->getResponse()->isSuccessful()) {
            file_put_contents(sprintf('/tmp/%s.log', str_replace('/', '-', $url)), $client->getResponse()->getContent());
        }

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider(): array
    {
        return [
            ['/en/ibd'],
            ['/en/meningitis'],
            ['/en/pneumonia'],
            ['/en/rota'],
            ['/en/ibd/reports/data-quality'],
            ['/en/ibd/reports/site-performance'],
            ['/en/ibd/reports/data-linking'],
            ['/en/ibd/reports/annual-age-distribution'],
            ['/en/ibd/reports/percent-enrolled'],
            ['/en/ibd/reports/field-population'],
            ['/en/ibd/reports/culture-positive'],
            ['/en/pneumonia/reports/data-quality'],
            ['/en/pneumonia/reports/site-performance'],
            ['/en/pneumonia/reports/data-linking'],
            ['/en/pneumonia/reports/annual-age-distribution'],
            ['/en/pneumonia/reports/percent-enrolled'],
            ['/en/meningitis/reports/data-quality'],
            ['/en/meningitis/reports/site-performance'],
            ['/en/meningitis/reports/data-linking'],
            ['/en/meningitis/reports/annual-age-distribution'],
            ['/en/meningitis/reports/percent-enrolled'],
            ['/en/meningitis/reports/field-population'],
            ['/en/meningitis/reports/culture-positive'],
            ['/en/rota/reports/data-quality'],
            ['/en/rota/reports/site-performance'],
            ['/en/rota/reports/data-linking'],
            ['/en/profile'],
            ['/en/zero-report'],
        ];
    }

    /**
     * @param $url
     * @param string $button
     * @param array $params
     *
     * @dataProvider getFormUrls
     * @group form
     */
    public function testFormSubmission($url, $button, array $params): void
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

    public function getFormUrls(): array
    {
        return [
//            array('/en/ibd','ibd_filter_form[find]',array('ibd_filter_form[id]'=>'123')),
//            array('/en/rota'),
            ['/en/ibd/reports/data-quality', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/ibd/reports/site-performance', 'base_quarterly_filter_filter', ['base_quarterly_filter[year]' => 2014]],
            ['/en/ibd/reports/data-linking', 'quarterly_linking_report_filter_filter', ['quarterly_linking_report_filter[year]' => 2014]],
            ['/en/ibd/reports/stats', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/ibd/reports/year-month', 'base_quarterly_filter_filter', ['base_quarterly_filter[year]' => 2014]],
            ['/en/ibd/reports/annual-age-distribution', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/ibd/reports/percent-enrolled', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/ibd/reports/field-population', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/ibd/reports/culture-positive', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],

            ['/en/pneumonia/reports/data-quality', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/pneumonia/reports/site-performance', 'base_quarterly_filter_filter', ['base_quarterly_filter[year]' => 2014]],
            ['/en/pneumonia/reports/year-month', 'base_quarterly_filter_filter', ['base_quarterly_filter[year]' => 2014]],
            ['/en/pneumonia/reports/data-linking', 'quarterly_linking_report_filter_filter', ['quarterly_linking_report_filter[year]' => 2014]],
            ['/en/pneumonia/reports/stats', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/pneumonia/reports/annual-age-distribution', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/pneumonia/reports/percent-enrolled', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],

            ['/en/meningitis/reports/data-quality', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/meningitis/reports/site-performance', 'base_quarterly_filter_filter', ['base_quarterly_filter[year]' => 2014]],
            ['/en/meningitis/reports/data-linking', 'quarterly_linking_report_filter_filter', ['quarterly_linking_report_filter[year]' => 2014]],
            ['/en/meningitis/reports/stats', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/meningitis/reports/year-month', 'base_quarterly_filter_filter', ['base_quarterly_filter[year]' => 2014]],
            ['/en/meningitis/reports/annual-age-distribution', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/meningitis/reports/percent-enrolled', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/meningitis/reports/field-population', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],
            ['/en/meningitis/reports/culture-positive', 'ibd_report_filter_filter', ['ibd_report_filter[adm_date][left_date]' => '01/01/2014', 'ibd_report_filter[adm_date][right_date]' => '12/31/2014']],

            ['/en/rota/reports/data-quality','rota_report_filter[filter]', ['rota_report_filter[adm_date][left_date]'=>'01/01/2014','rota_report_filter[adm_date][right_date]'=>'12/31/2014']],
            ['/en/rota/reports/site-performance','base_quarterly_filter_filter', ['base_quarterly_filter[year]'=>2014]],
            ['/en/rota/reports/year-month','base_quarterly_filter_filter', ['base_quarterly_filter[year]'=>2014]],
            ['/en/rota/reports/data-linking','quarterly_linking_report_filter_filter', ['quarterly_linking_report_filter[year]'=>2014]],
//            array('/en/profile','submit',array('ns_sentinelbundle_user[name]'=>'Test User Name')),
        ];
    }

    /**
     * @param string $email
     * @param string $url
     * @param int $expectedResponseCode
     *
     * @dataProvider getAdminUrls
     * @group adminUrls
     */
    public function testAdminUrls($email, $url, $expectedResponseCode): void
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

    public function getAdminUrls(): array
    {
        $super   = 'superadmin@noblet.ca';
        $region  = 'na@noblet.ca';
        $country = 'ca@noblet.ca';

        return [
            // Super
            [$super, '/en/admin/dashboard', 200],
            [$super, '/en/admin/ns/sentinel/region/create', 200],
            [$super, '/en/admin/ns/sentinel/region/NA/show', 200],
//            array($super, '/en/admin/ns/sentinel/region/NA/delete', 403),
            [$super, '/en/admin/ns/sentinel/region/list', 200],
            [$super, '/en/admin/ns/sentinel/country/create', 200],
            [$super, '/en/admin/ns/sentinel/country/list', 200],
            [$super, '/en/admin/ns/sentinel/country/CA/show', 200],
//            array($super, '/en/admin/ns/sentinel/country/CA/delete', 403),
            [$super, '/en/admin/ns/sentinel/site/create', 200],
            [$super, '/en/admin/ns/sentinel/site/list', 200],
            [$super, '/en/admin/ns/sentinel/site/ALBCHLD/show', 200],
//            array($super, '/en/admin/ns/sentinel/site/ALBCHLD/delete', 403),
            [$super, '/en/admin/ns/sentinel/referencelab/create', 200],
            [$super, '/en/admin/ns/sentinel/referencelab/list', 200],
            [$super, '/en/admin/ns/sentinel/acl/create', 200],
            [$super, '/en/admin/ns/sentinel/user/create', 200],
            [$super, '/en/admin/ns/sentinel/user/list', 200],
            [$super, '/en/admin/ns/api/client/create', 200],
            [$super, '/en/admin/ns/api/client/list', 200],
            [$super, '/en/admin/ns/api/remote/create', 200],
            [$super, '/en/admin/ns/api/remote/list', 200],
            [$super, '/en/admin/ns/import/map/create', 200],
            [$super, '/en/admin/ns/import/map/list', 200],

            // Region
            [$region, '/en/admin/dashboard', 200],
            [$region, '/en/admin/ns/sentinel/region/create', 403],
            [$region, '/en/admin/ns/sentinel/region/list', 200],
            [$region, '/en/admin/ns/sentinel/region/NA/show', 200],
            [$region, '/en/admin/ns/sentinel/region/NA/delete', 403],
            [$region, '/en/admin/ns/sentinel/country/create', 200],
            [$region, '/en/admin/ns/sentinel/country/list', 200],
            [$region, '/en/admin/ns/sentinel/country/CA/show', 200],
            [$region, '/en/admin/ns/sentinel/country/CA/delete', 403],
            [$region, '/en/admin/ns/sentinel/site/create', 200],
            [$region, '/en/admin/ns/sentinel/site/list', 200],
            [$region, '/en/admin/ns/sentinel/site/ALBCHLD/show', 200],
            [$region, '/en/admin/ns/sentinel/site/ALBCHLD/delete', 403],
            [$region, '/en/admin/ns/sentinel/referencelab/create', 200],
            [$region, '/en/admin/ns/sentinel/referencelab/list', 200],
            [$region, '/en/admin/ns/sentinel/acl/create', 200],
            [$region, '/en/admin/ns/sentinel/user/create', 200],
            [$region, '/en/admin/ns/sentinel/user/list', 200],
            [$region, '/en/admin/ns/api/client/create', 403],
            [$region, '/en/admin/ns/api/client/list', 403],
            [$region, '/en/admin/ns/api/remote/create', 403],
            [$region, '/en/admin/ns/api/remote/list', 403],
            [$region, '/en/admin/ns/import/map/create', 200],
            [$region, '/en/admin/ns/import/map/list', 200],

            //Country
            [$country, '/en/admin/dashboard', 200],
            [$country, '/en/admin/ns/sentinel/region/create', 403],
            [$country, '/en/admin/ns/sentinel/region/list', 403],
            [$country, '/en/admin/ns/sentinel/region/NA/show', 403],
            [$country, '/en/admin/ns/sentinel/region/NA/delete', 403],
            [$country, '/en/admin/ns/sentinel/country/create', 403],
            [$country, '/en/admin/ns/sentinel/country/list', 200],
            [$country, '/en/admin/ns/sentinel/country/CA/show', 200],
            [$country, '/en/admin/ns/sentinel/country/CA/delete', 403],
            [$country, '/en/admin/ns/sentinel/site/create', 200],
            [$country, '/en/admin/ns/sentinel/site/list', 200],
            [$country, '/en/admin/ns/sentinel/site/ALBCHLD/show', 200],
            [$country, '/en/admin/ns/sentinel/site/ALBCHLD/delete', 403],
            [$country, '/en/admin/ns/sentinel/referencelab/create', 403],
            [$country, '/en/admin/ns/sentinel/referencelab/list', 403],
            [$country, '/en/admin/ns/sentinel/acl/create', 200],
            [$country, '/en/admin/ns/sentinel/user/create', 200],
            [$country, '/en/admin/ns/sentinel/user/list', 200],
            [$country, '/en/admin/ns/api/client/create', 403],
            [$country, '/en/admin/ns/api/client/list', 403],
            [$country, '/en/admin/ns/api/remote/create', 403],
            [$country, '/en/admin/ns/api/remote/list', 403],
            [$country, '/en/admin/ns/import/map/create', 403],
            [$country, '/en/admin/ns/import/map/list', 403],
        ];
    }

    private function getClient($email): Client
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
