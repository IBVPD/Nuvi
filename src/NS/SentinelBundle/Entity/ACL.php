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
     * @var Role $type
     *
     * @ORM\Column(name="type",type="Role")
     */
    protected $type;

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
}