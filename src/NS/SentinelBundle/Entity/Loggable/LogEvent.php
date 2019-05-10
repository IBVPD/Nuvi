<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 11/05/16
 * Time: 2:54 PM
 */

namespace NS\SentinelBundle\Entity\Loggable;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class LogEvent
 * @package NS\SentinelBundle\Entity\Loggable
 *
 * @ORM\Entity
 * @ORM\Table(name="event_logs")
 */
class LogEvent
{
    public const CREATED = 'created';
    public const UPDATED = 'updated';
    public const DELETED = 'deleted';


    /**
     * @var int
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="username",type="string")
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(name="action",type="string")
     */
    private $action;

    /**
     * @var DateTime
     * @ORM\Column(name="logged_at",type="datetime")
     */
    private $logged_at;

    /**
     * @var string
     * @ORM\Column(name="object_id",type="string")
     */
    private $object_id;

    /**
     * @var string
     * @ORM\Column(name="object_class",type="string")
     */
    private $object_class;

    /**
     * @var string
     * @ORM\Column(name="data",type="text",nullable=true)
     */
    private $data;

    /**
     * LogEvent constructor.
     * @param string $username
     * @param string $action
     * @param string $object_id
     * @param string $object_class
     */
    public function __construct($username, $action, $object_id, $object_class)
    {
        $this->username = $username;
        $this->action = $action;
        $this->logged_at = new DateTime();
        $this->object_id = $object_id;
        $this->object_class = $object_class;
    }


    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return DateTime
     */
    public function getLoggedAt()
    {
        return $this->logged_at;
    }

    /**
     * @return string
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * @return string
     */
    public function getObjectClass()
    {
        return $this->object_class;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return LogEvent
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return LogEvent
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
}
