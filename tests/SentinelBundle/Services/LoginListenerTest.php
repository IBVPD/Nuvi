<?php

namespace NS\SentinelBundle\Tests\Services;

use NS\SentinelBundle\Services\LoginListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver;
use NS\SentinelBundle\Services\Homepage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Description of LoginListenerTest
 *
 * @author gnat
 */
class LoginListenerTest extends TestCase
{

    public function testListenerNotAppliedToApi(): void
    {
        $homepage   = $this->createMock(Homepage::class);
        $dispatcher = $this->createMock(EventDispatcher::class);
        $dispatcher->expects($this->never())
            ->method('addListener');

        $listener            = new LoginListener($homepage, $dispatcher);
        $request             = new Request([], [], ['_route' => 'apiLoginCheck']);
        $authenticationToken = $this->createMock(TokenInterface::class);

        $listener->onSecurityInteractiveLogin(new InteractiveLoginEvent($request, $authenticationToken));
    }

    public function testListenerIsApplied(): void
    {
        $homepage   = $this->createMock(Homepage::class);
        $dispatcher = $this->createMock(EventDispatcher::class);
        $dispatcher->expects($this->once())
            ->method('addListener');

        $listener            = new LoginListener($homepage, $dispatcher);
        $request             = new Request([], [], ['_route' => 'login_check']);
        $authenticationToken = $this->createMock(TokenInterface::class);

        $listener->onSecurityInteractiveLogin(new InteractiveLoginEvent($request, $authenticationToken));
    }

    public function testListenerIsCalled(): void
    {
        $homepage = $this->createMock(Homepage::class);
        $homepage->expects($this->once())
            ->method('getHomepageResponse')
            ->willReturn(new RedirectResponse('someUrl'));

        $dispatcher = new EventDispatcher();
        $container  = $this->createMock(ControllerResolver::class);
        $listener   = new LoginListener($homepage, $dispatcher);
        $kernel     = new HttpKernel($dispatcher, $container, null, new ArgumentResolver());
        $response   = new Response();
        $event      = new FilterResponseEvent($kernel, new Request(), HttpKernel::MASTER_REQUEST, $response);
        $listener->onKernelResponse($event);
        $this->assertNotEquals($event->getResponse(), $response);
        $this->assertInstanceOf(RedirectResponse::class, $event->getResponse());
    }
}
