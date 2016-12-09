<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 12/05/16
 * Time: 12:51 PM
 */

namespace NS\SentinelBundle\Tests\Loggable;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Loggable\LogEvent;
use NS\SentinelBundle\Entity\User;
use NS\SentinelBundle\Loggable\LoggableListener;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoggableListenerTest extends \PHPUnit_Framework_TestCase
{
    public function getListener($expects,$returns = null)
    {
        $tokenStorage = $this->createMock('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');

        $tokenStorage->expects($expects)
            ->method('getToken')
            ->willReturn($returns);

        $serializer = $this->createMock('JMS\Serializer\Serializer');

        $serializer->expects($this->any())
            ->method('serialize')
            ->willReturn('str');

        return new LoggableListener($tokenStorage,$serializer);
    }

    public function testCreatedEvent()
    {
        $listener = $this->getListener($this->once());
        $ibdCase = new IBD();
        $event = $listener->getLogEvent(LogEvent::CREATED, 'EVENTID', $ibdCase);
        $this->assertEquals($event->getAction(), LogEvent::CREATED);
        $this->assertNull($event->getData());
        $this->assertEquals($event->getObjectClass(), 'NS\SentinelBundle\Entity\IBD');
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'anon');
    }

    public function testUpdatedEvent()
    {
        $listener = $this->getListener($this->once());
        $ibdCase = new IBD();
        $event = $listener->getLogEvent(LogEvent::UPDATED, 'EVENTID', $ibdCase);
        $this->assertEquals($event->getAction(), LogEvent::UPDATED);
        $this->assertNull($event->getData());
        $this->assertEquals($event->getObjectClass(), 'NS\SentinelBundle\Entity\IBD');
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'anon');
    }

    public function testDeletedEvent()
    {
        $listener = $this->getListener($this->once());
        $ibdCase = new IBD();
        $ibdCase->setId('EVENTID');
        $event = $listener->getLogEvent(LogEvent::DELETED, 'EVENTID', $ibdCase);
        $this->assertEquals($event->getAction(), LogEvent::DELETED);
        $this->assertNotNull($event->getData());
        $this->assertEquals($event->getObjectClass(), 'NS\SentinelBundle\Entity\IBD');
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'anon');
    }

    public function testSetUsername()
    {
        $listener = $this->getListener($this->never());
        $listener->setUsername('nathanael@gnat.ca');
        $ibdCase = new IBD();
        $event = $listener->getLogEvent(LogEvent::CREATED, 'EVENTID', $ibdCase);
        $this->assertEquals($event->getAction(), LogEvent::CREATED);
        $this->assertNull($event->getData());
        $this->assertEquals($event->getObjectClass(), 'NS\SentinelBundle\Entity\IBD');
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'nathanael@gnat.ca');
    }

    public function testTokenHasUser()
    {
        $user = new User();
        $user->setEmail('nathanael@gnat.ca');
        $token = new UsernamePasswordToken($user, '', 'provider', $user->getRoles());

        $listener = $this->getListener($this->atLeast(3),$token);
        $ibdCase = new IBD();
        $event = $listener->getLogEvent(LogEvent::CREATED, 'EVENTID', $ibdCase);
        $this->assertEquals($event->getAction(), LogEvent::CREATED);
        $this->assertNull($event->getData());
        $this->assertEquals($event->getObjectClass(), 'NS\SentinelBundle\Entity\IBD');
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'nathanael@gnat.ca');
    }

    public function testSerializationParameters()
    {
        $tokenStorage = $this->createMock('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');

        $tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn(null);

        $serializer = $this->createMock('JMS\Serializer\Serializer');

        $ibd = $this->getIbdCase();

        $serializer->expects($this->any())
            ->method('serialize')
            ->with($ibd,'json',SerializationContext::create()->setGroups(array('api','delete')))
            ->willReturn(json_encode(array('something')));

        $listener = new LoggableListener($tokenStorage,$serializer);
        $event = $listener->getLogEvent(LogEvent::DELETED, 'EVENTID', $ibd);
        $this->assertEquals($event->getAction(), LogEvent::DELETED);
        $this->assertJson($event->getData());
        $this->assertEquals($event->getObjectClass(), 'NS\SentinelBundle\Entity\IBD');
        $this->assertEquals($event->getObjectId(), 'EVENTID');
        $this->assertEquals($event->getUsername(), 'anon');
    }

    public function testSerialization()
    {
        $serializer = SerializerBuilder::create()->build();
        $ibd = $this->getIbdCase();
        
        $json = $serializer->serialize($ibd, 'json', SerializationContext::create()->setGroups(array('api','delete')));
        $this->assertJson($json);
    }

    private function getIbdCase()
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
