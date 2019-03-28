<?php

namespace NS\SentinelBundle\Tests\Loggable;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Loggable\LogEvent;
use NS\SentinelBundle\Entity\User;
use NS\SentinelBundle\Loggable\LoggableListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use JMS\Serializer\Serializer;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LoggableListenerTest extends TestCase
{
    public function getListener($expects,$returns = null): LoggableListener
    {
        $tokenStorage = $this->createMock(TokenStorageInterface::class);

        $tokenStorage->expects($expects)
            ->method('getToken')
            ->willReturn($returns);

        $serializer = $this->createMock(Serializer::class);

        $serializer
            ->method('serialize')
            ->willReturn('str');

        return new LoggableListener($tokenStorage,$serializer);
    }

    public function testCreatedEvent(): void
    {
        $listener = $this->getListener($this->once());
        $ibdCase = new IBD();
        $event = $listener->getLogEvent(LogEvent::CREATED, 'EVENTID', $ibdCase);
        $this->assertEquals($event->getAction(), LogEvent::CREATED);
        $this->assertNull($event->getData());
        $this->assertEquals($event->getObjectClass(), IBD::class);
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'anon');
    }

    public function testUpdatedEvent(): void
    {
        $listener = $this->getListener($this->once());
        $ibdCase = new IBD();
        $event = $listener->getLogEvent(LogEvent::UPDATED, 'EVENTID', $ibdCase);
        $this->assertEquals($event->getAction(), LogEvent::UPDATED);
        $this->assertNull($event->getData());
        $this->assertEquals($event->getObjectClass(), IBD::class);
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'anon');
    }

    public function testDeletedEvent(): void
    {
        $listener = $this->getListener($this->once());
        $ibdCase = new IBD();
        $ibdCase->setId('EVENTID');
        $event = $listener->getLogEvent(LogEvent::DELETED, 'EVENTID', $ibdCase);
        $this->assertEquals($event->getAction(), LogEvent::DELETED);
        $this->assertNotNull($event->getData());
        $this->assertEquals($event->getObjectClass(), IBD::class);
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'anon');
    }

    public function testSetUsername(): void
    {
        $listener = $this->getListener($this->never());
        $listener->setUsername('nathanael@gnat.ca');
        $ibdCase = new IBD();
        $event = $listener->getLogEvent(LogEvent::CREATED, 'EVENTID', $ibdCase);
        $this->assertEquals($event->getAction(), LogEvent::CREATED);
        $this->assertNull($event->getData());
        $this->assertEquals($event->getObjectClass(), IBD::class);
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'nathanael@gnat.ca');
    }

    public function testTokenHasUser(): void
    {
        $user = new User();
        $user->setEmail('nathanael@gnat.ca');
        $token = new UsernamePasswordToken($user, '', 'provider', $user->getRoles());

        $listener = $this->getListener($this->atLeast(3),$token);
        $ibdCase = new IBD();
        $event = $listener->getLogEvent(LogEvent::CREATED, 'EVENTID', $ibdCase);
        $this->assertEquals($event->getAction(), LogEvent::CREATED);
        $this->assertNull($event->getData());
        $this->assertEquals($event->getObjectClass(), IBD::class);
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'nathanael@gnat.ca');
    }

    public function testSerializationParameters(): void
    {
        $tokenStorage = $this->createMock(TokenStorageInterface::class);

        $tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn(null);

        $serializer = $this->createMock(Serializer::class);

        $ibd = $this->getIbdCase();

        $serializer
            ->method('serialize')
            ->with($ibd,'json',SerializationContext::create()->setGroups(['api','delete']))
            ->willReturn(json_encode(['something']));

        $listener = new LoggableListener($tokenStorage,$serializer);
        $event = $listener->getLogEvent(LogEvent::DELETED, 'EVENTID', $ibd);
        $this->assertEquals($event->getAction(), LogEvent::DELETED);
        $this->assertJson($event->getData());
        $this->assertEquals($event->getObjectClass(), IBD::class);
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'anon');
    }

    public function testSerialization(): void
    {
        $serializer = SerializerBuilder::create()->build();
        $ibd = $this->getIbdCase();
        
        $json = $serializer->serialize($ibd, 'json', SerializationContext::create()->setGroups(['api','delete']));
        $this->assertJson($json);
    }

    private function getIbdCase(): IBD
    {
        $nl = new IBD\NationalLab();
        $nl->setLabId('nl-labId-1');

        $rrl = new IBD\ReferenceLab();
        $rrl->setLabId('rrl-labId-1');

        $siteLab = new IBD\SiteLab();
        $siteLab->setCsfId('1234');
        $siteLab->setCsfWcc(1234);

        $ibd = new IBD();
        $ibd->setId('CA-XXX-15-000015');
        $ibd->setSiteLab($siteLab);
        $ibd->setNationalLab($nl);
        $ibd->setReferenceLab($rrl);

        return $ibd;
    }
}
