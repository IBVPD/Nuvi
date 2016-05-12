<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 11/05/16
 * Time: 3:12 PM
 */

namespace NS\SentinelBundle\Loggable;


use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\Loggable\LogEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LoggableListener
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Serializer $serializer
     */
    private $serializer;

    /**
     * LoggableListener constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param Serializer $serializer
     */
    public function __construct(TokenStorageInterface $tokenStorage, Serializer $serializer)
    {
        $this->tokenStorage = $tokenStorage;
        $this->serializer = $serializer;
    }

    /**
     * @param $event
     * @param $identifier
     * @param $object
     * @return LogEvent
     */
    public function getLogEvent($event, $identifier, $object)
    {
        if (!$this->username) {
            $this->findUsername();
        }

        $eventLog = new LogEvent($this->username, $event, $identifier, get_class($object));

        if ($event === LogEvent::DELETED) {
            $eventLog->setData($this->extractData($object));
        }

        return $eventLog;
    }

    /**
     * Attempt to find the username from the current token or set it to anonymous
     */
    private function findUsername()
    {
        if ($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser() instanceof UserInterface) {
            $this->username = $this->tokenStorage->getToken()->getUser()->getUsername();
        } else {
            $this->username = 'anon';
        }
    }

    /**
     * @param BaseCase $object
     * @return string
     */
    public function extractData(BaseCase $object)
    {
        return $this->serializer->serialize($object, 'json', SerializationContext::create()->setGroups(array('api','delete')));
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
}
