<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \NS\SecurityBundle\Entity\BaseACL;

/**
 * ACL
 *
 * @ORM\Table(name="acls")
 * @ORM\Entity
 */
class ACL extends BaseACL
{
    /**
     * @var User $user
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="acls")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @var Role $type
     *
     * @ORM\Column(name="type",type="Role")
     */
    protected $type;

    /**
     * @var string $object_id
     *
     * @ORM\Column(name="object_id",type="string",length=15)
     */
    protected $object_id;

    /**
     * Set type
     *
     * @param \Role $type
     * @return ACL
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return \Role 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set user
     *
     * @param \NS\SentinelBundle\Entity\User $user
     * @return ACL
     */
    public function setUser(\NS\SentinelBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \NS\SentinelBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
