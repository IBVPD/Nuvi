<?php

namespace NS\SentinelBundle\Tests\Services;

use NS\SentinelBundle\Services\Homepage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use NS\SentinelBundle\Entity\User;

/**
 * Description of HomepageTest
 *
 * @author gnat
 */
class HomepageTest extends TestCase
{
    public function testOnlyAdmin(): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('isOnlyAdmin')
            ->willReturn(true);

        $homepage = $this->getHomepageService($user, 'sonata_admin_dashboard');
        $request  = new Request();
        $response = $homepage->getHomepageResponse($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($response->getTargetUrl(), 'sonata/admin/dashboard');
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * @dataProvider getRequests
     * @param $request
     * @param $locale
     */
    public function testNeitherOnlyAdminNorOnlyApi($request, $locale): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('isOnlyAdmin')
            ->willReturn(false);

        $homepage = $this->getHomepageService($user, 'homepage', $locale);
        $response = $homepage->getHomepageResponse($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($response->getTargetUrl(), sprintf('/%s', $locale['_locale']));
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function getRequests(): array
    {
        return [
            [
                'request' => new Request([], [], ['_locale' => 'en']),
                'locale'  => ['_locale' => 'en'],
            ],
            [
                'request' => new Request([], [], ['_locale' => 'fr']),
                'locale'  => ['_locale' => 'fr'],
            ],
            [
                'request' => new Request([], [], ['_locale' => 'hi']),
                'locale'  => ['_locale' => 'hi'],
            ],
            [
                'request' => new Request([], [], ['_locale' => 'pt']),
                'locale'  => ['_locale' => 'pt'],
            ],
        ];
    }

    private function getHomepageService($user, $route, $routerParam = null): Homepage
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $router = $this->createMock(RouterInterface::class);
        if ($routerParam) {
            $router->expects($this->once())
                ->method('generate')
                ->with($route, $routerParam)
                ->willReturn(sprintf('/%s', $routerParam['_locale']));
        } else {
            $router->expects($this->once())
                ->method('generate')
                ->with($route)
                ->willReturn(str_replace('_', '/', $route));
        }

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn($token);

        return new Homepage($tokenStorage, $router);
    }
}
