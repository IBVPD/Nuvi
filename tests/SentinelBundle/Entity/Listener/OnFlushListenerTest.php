<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 11/05/16
 * Time: 1:23 PM
 */

namespace NS\SentinelBundle\Tests\Entity\Listener;

use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Tests\BaseDBTestCase;

class OnFlushListenerTest extends BaseDBTestCase
{

    public function testLink()
    {
        $case = $this->entityManager->getRepository('NSSentinelBundle:IBD')->findWithAssociations('CA-XXX-16-18bae006-ac0b-439d-a749-936abb435a7d');
        $this->assertInstanceOf(IBD::class, $case);
        $this->assertInstanceOf(Region::class, $case->getRegion());

        $site = $this->entityManager
            ->getRepository('NSSentinelBundle:Site')
            ->createQueryBuilder('s')
            ->select('s,c,r')
            ->leftJoin('s.country','c')
            ->leftJoin('c.region','r')
            ->where('s.country = :country')
            ->setParameter('country',$this->entityManager->getReference(Country::class,'CA'))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;

        $this->assertInstanceOf(Site::class, $site);

        $this->assertTrue($case->isUnlinked());
        $case->setSite($site);
        $this->entityManager->persist($case);
        $this->entityManager->flush();
    }
}
