<?php

namespace NS\SentinelBundle\Tests\Listeners;

use NS\SentinelBundle\Listeners\DoctrineExtensionListener;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Description of DoctrineExtenstionListenerTest
 *
 * @author gnat
 */
class DoctrineExtenstionListenerTest extends PHPUnit_Framework_TestCase
{
    public function testNoToken()
    {
        $securityContext = $this->getMockBuilder('\Symfony\Component\Security\Core\SecurityContextInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $securityContext->expects($this->once())
            ->method('getToken')
            ->willReturn(null);

        $loggable = $this->getMockBuilder('Gedmo\Loggable\LoggableListener')
            ->disableOriginalConstructor()
            ->getMock();
        $loggable->expects($this->never())
            ->method('setUsername');

        $listener = new DoctrineExtensionListener($securityContext,$loggable);
        $listener->onKernelRequest($this->getEvent());
    }

    public function testIsNotAuthenticated()
    {
        $securityContext = $this->getMockBuilder('\Symfony\Component\Security\Core\SecurityContextInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $securityContext->expects($this->once())
            ->method('getToken')
            ->willReturn(' ');
        $securityContext->expects($this->once())
            ->method('isGranted')
            ->with('IS_AUTHENTICATED_FULLY')
            ->willReturn(false);

        $loggable = $this->getMockBuilder('Gedmo\Loggable\LoggableListener')
            ->disableOriginalConstructor()
            ->getMock();
        $loggable->expects($this->never())
            ->method('setUsername');

        $listener = new DoctrineExtensionListener($securityContext,$loggable);
        $listener->onKernelRequest($this->getEvent());
    }

    public function testIsAuthenticated()
    {
        $token = $this->getMockBuilder('\Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $token->expects($this->once())
            ->method('getUsername')
            ->willReturn('gnat');
        $securityContext = $this->getMockBuilder('\Symfony\Component\Security\Core\SecurityContextInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $securityContext->expects($this->any())
            ->method('getToken')
            ->willReturn($token);
        $securityContext->expects($this->once())
            ->method('isGranted')
            ->with('IS_AUTHENTICATED_FULLY')
            ->willReturn(true);

        $loggable = $this->getMockBuilder('Gedmo\Loggable\LoggableListener')
            ->disableOriginalConstructor()
            ->getMock();
        $loggable->expects($this->once())
            ->method('setUsername')
            ->with('gnat');

        $listener = new DoctrineExtensionListener($securityContext,$loggable);
        $listener->onKernelRequest($this->getEvent());
    }

    public function getEvent()
    {
        $kernel = $this->getMockBuilder('\Symfony\Component\HttpKernel\HttpKernelInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $request = new Request();

        return new GetResponseEvent($kernel,  $request, HttpKernel::MASTER_REQUEST);
    }
}
