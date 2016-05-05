<?php

namespace NS\SentinelBundle\Tests\Services;

use \NS\SentinelBundle\Services\Homepage;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Description of HomepageTest
 *
 * @author gnat
 */
class HomepageTest extends \PHPUnit_Framework_TestCase
{

    public function testOnlyAdmin()
    {
        $user = $this->getMock('\NS\SentinelBundle\Entity\User');
        $user->expects($this->once())
            ->method('isOnlyAdmin')
            ->willReturn(true);

        $user->expects($this->never())
            ->method('isOnlyApi');

        $homepage = $this->getHomepageService($user, 'sonata_admin_dashboard');
        $request  = new Request();
        $response = $homepage->getHomepageResponse($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals($response->getTargetUrl(), 'sonata/admin/dashboard');
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testOnlyApi()
    {
        $user = $this->getMock('\NS\SentinelBundle\Entity\User');
        $user->expects($this->once())
            ->method('isOnlyAdmin')
            ->willReturn(false);

        $user->expects($this->once())
            ->method('isOnlyApi')
            ->willReturn(true);

        $homepage = $this->getHomepageService($user, 'ns_api_dashboard');
        $request  = new Request();
        $response = $homepage->getHomepageResponse($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals($response->getTargetUrl(), 'ns/api/dashboard');
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * @dataProvider getRequests
     * @param $request
     * @param $locale
     */
    public function testNeitherOnlyAdminNorOnlyApi($request, $locale)
    {
        $user = $this->getMock('\NS\SentinelBundle\Entity\User');
        $user->expects($this->once())
            ->method('isOnlyAdmin')
            ->willReturn(false);

        $user->expects($this->once())
            ->method('isOnlyApi')
            ->willReturn(false);

        $homepage = $this->getHomepageService($user, 'homepage', $locale);
        $response = $homepage->getHomepageResponse($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals($response->getTargetUrl(), sprintf("/%s", $locale['_locale']));
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function getRequests()
    {
        return array(
            array(
                'request' => new Request(array(), array(), array('_locale' => 'en')),
                'locale'  => array('_locale' => 'en'),
            ),
            array(
                'request' => new Request(array(), array(), array('_locale' => 'fr')),
                'locale'  => array('_locale' => 'fr'),
            ),
            array(
                'request' => new Request(array(), array(), array('_locale' => 'hi')),
                'locale'  => array('_locale' => 'hi'),
            ),
            array(
                'request' => new Request(array(), array(), array('_locale' => 'pt')),
                'locale'  => array('_locale' => 'pt'),
            ),
        );
    }

    private function getHomepageService($user, $route, $routerParam = null)
    {
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $router = $this->getMock('\Symfony\Component\Routing\RouterInterface');
        if ($routerParam) {
            $router->expects($this->once())
                ->method('generate')
                ->with($route, $routerParam)
                ->willReturn(sprintf("/%s", $routerParam['_locale']));
        } else {
            $router->expects($this->once())
                ->method('generate')
                ->with($route)
                ->willReturn(str_replace("_", "/", $route));
        }

        $tokenStorage = $this->getMock('\Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn($token);

        return new Homepage($tokenStorage, $router);
    }
}
