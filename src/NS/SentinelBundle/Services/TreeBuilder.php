<?php

namespace NS\SentinelBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of TreeBuilder
 *
 * @author gnat
 */
class TreeBuilder
{
    private $em;
    
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }
    
    public function build()
    {
        $regions = $this->em->getRepository('NSSentinelBundle:Region')->getAllForTree();
        
//        foreach($regions as )
        
    }
}
