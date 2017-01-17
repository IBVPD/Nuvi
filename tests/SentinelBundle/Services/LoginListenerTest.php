<?php

namespace NS\SentinelBundle\Tests\Services;

use NS\SentinelBundle\Services\LoginListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Description of LoginListenerTest
 *
 * @author gnat
 */
class LoginListenerTest extends \PHPUnit_Framework_TestCase
{

    public function testListenerNotAppliedToApi()
    {
        $homepage   = $this->createMock('NS\SentinelBundle\Services\Homepage');
        $dispatcher = $this->createMock('\Symfony\Component\EventDispatcher\EventDispatcher');
        $dispatcher->expects($this->never())
            ->method('addListener');

        $listener            = new LoginListener($homepage, $dispatcher);
        $request             = new Request([], [], ['_route' => 'apiLoginCheck']);
        $authenticationToken = $this->createMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        $listener->onSecurityInteractiveLogin(new InteractiveLoginEvent($request, $authenticationToken));
    }

    public function testListenerIsApplied()
    {
        $homepage   = $this->createMock('NS\SentinelBundle\Services\Homepage');
        $dispatcher = $this->createMock('\Symfony\Component\EventDispatcher\EventDispatcher');
        $dispatcher->expects($this->once())
            ->method('addListener');

        $listener            = new LoginListener($homepage, $dispatcher);
        $request             = new Request([], [], ['_route' => 'login_check']);
        $authenticationToken = $this->createMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        $listener->onSecurityInteractiveLogin(new InteractiveLoginEvent($request, $authenticationToken));
    }

    public function testListenerIsCalled()
    {
        $homepage = $this->createMock('NS\SentinelBundle\Services\Homepage');
        $homepage->expects($this->once())
            ->method('getHomepageResponse')
            ->willReturn(new RedirectResponse('someUrl'));

        $dispatcher = new EventDispatcher();
        $container  = $this->createMock('\Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver');
        $listener   = new LoginListener($homepage, $dispatcher);
        $kernel     = new HttpKernel($dispatcher, $container, null, new ArgumentResolver());
        $response   = new Response();
        $event      = new FilterResponseEvent($kernel, new Request(), HttpKernel::MASTER_REQUEST, $response);
        $listener->onKernelResponse($event);
        $this->assertNotEquals($event->getResponse(), $response);
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\RedirectResponse', $event->getResponse());
    }
}
