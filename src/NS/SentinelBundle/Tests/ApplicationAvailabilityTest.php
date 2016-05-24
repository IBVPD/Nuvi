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
        $client = $this->getClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $url);

        $form = $crawler->selectButton($button)->form();
        foreach($params as $key=>$value) {
            $form[$key] = $value;
        }

        $client->submit($form);

        if(!$client->getResponse()->isSuccessful()) {
            file_put_contents(sprintf('/tmp/%s-form.log',str_replace('/','-',$url)),$client->getResponse()->getContent());
        }
    }

    public function getFormUrls()
    {
        return array(
//            array('/en/ibd','ibd_filter_form[find]',array('ibd_filter_form[id]'=>'123')),
//            array('/en/rota'),
            array('/en/ibd/reports/data-quality','IBDReportFilterType[filter]', array('IBDReportFilterType[adm_date][left_date]'=>'01/01/2014','IBDReportFilterType[adm_date][right_date]'=>'12/31/2014')),
            array('/en/ibd/reports/site-performance','QuarterlyReportFilter[filter]', array('QuarterlyReportFilter[year]'=>2014)),
            array('/en/ibd/reports/data-linking','IBDQuarterlyLinkingReportFilter[filter]', array('IBDQuarterlyLinkingReportFilter[year]'=>2014)),
            array('/en/ibd/reports/annual-age-distribution','IBDReportFilterType[filter]', array('IBDReportFilterType[adm_date][left_date]'=>'01/01/2014','IBDReportFilterType[adm_date][right_date]'=>'12/31/2014')),
            array('/en/ibd/reports/percent-enrolled','IBDReportFilterType[filter]', array('IBDReportFilterType[adm_date][left_date]'=>'01/01/2014','IBDReportFilterType[adm_date][right_date]'=>'12/31/2014')),
            array('/en/ibd/reports/field-population','IBDReportFilterType[filter]', array('IBDReportFilterType[adm_date][left_date]'=>'01/01/2014','IBDReportFilterType[adm_date][right_date]'=>'12/31/2014')),
            array('/en/ibd/reports/culture-positive','IBDReportFilterType[filter]', array('IBDReportFilterType[adm_date][left_date]'=>'01/01/2014','IBDReportFilterType[adm_date][right_date]'=>'12/31/2014')),
            array('/en/rota/reports/data-quality','RotaVirusReportFilterType[filter]', array('RotaVirusReportFilterType[adm_date][left_date]'=>'01/01/2014','RotaVirusReportFilterType[adm_date][right_date]'=>'12/31/2014')),
            array('/en/rota/reports/site-performance','QuarterlyReportFilter[filter]', array('QuarterlyReportFilter[year]'=>2014)),
            array('/en/rota/reports/data-linking','RotaVirusQuarterlyLinkingReportFilter[filter]', array('RotaVirusQuarterlyLinkingReportFilter[year]'=>2014)),
//            array('/en/profile','submit',array('ns_sentinelbundle_user[name]'=>'Test User Name')),
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
