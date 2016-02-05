<?php

namespace NS\SentinelBundle\Entity\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\Site;

class OnFlushListener
{
    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $entityMgr = $eventArgs->getEntityManager();
        $uow = $entityMgr->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if($entity instanceof BaseCase) {
                $this->checkLinking($entity,$entityMgr);
            }
        }
    }

    /**
     * @param BaseCase $case
     * @param ObjectManager $manager
     */
    public function checkLinking(BaseCase $case, ObjectManager $manager)
    {
        // Have a case with a newly assigned site
        if ($case->getSite() instanceof Site && $case->isUnlinked()) {
            $newCase = clone $case;

            $manager->remove($case);
            $manager->persist($newCase);
            $manager->getUnitOfWork()->computeChangeSets();
        }
    }
}
