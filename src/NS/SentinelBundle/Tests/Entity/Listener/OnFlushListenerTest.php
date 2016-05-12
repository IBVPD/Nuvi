<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 11/05/16
 * Time: 1:23 PM
 */

namespace NS\SentinelBundle\Tests\Entity\Listener;

use NS\SentinelBundle\Tests\BaseDBTestCase;

class OnFlushListenerTest extends BaseDBTestCase
{
    public function testLink()
    {
        $case = $this->entityManager->getRepository('NSSentinelBundle:IBD')->findWithAssociations('CA-XXX-16-278cec6f-a9b2-434e-a278-a03db2adbf7e');
        $this->assertInstanceOf('NS\SentinelBundle\Entity\IBD', $case);
        $this->assertInstanceOf('NS\SentinelBundle\Entity\Region', $case->getRegion());

        $site = $this->entityManager
            ->getRepository('NSSentinelBundle:Site')
            ->createQueryBuilder('s')
            ->select('s,c,r')
            ->leftJoin('s.country','c')
            ->leftJoin('c.region','r')
            ->where('s.country = :country')
            ->setParameter('country',$this->entityManager->getReference('NS\SentinelBundle\Entity\Country','CA'))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;

        $this->assertInstanceOf('NS\SentinelBundle\Entity\Site', $site);

        $this->assertTrue($case->isUnlinked());
        $case->setSite($site);
        $this->entityManager->persist($case);
        $this->entityManager->flush();
    }
}
