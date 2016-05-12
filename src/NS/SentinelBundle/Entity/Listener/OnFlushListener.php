<?php

namespace NS\SentinelBundle\Entity\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\BaseExternalLab;
use NS\SentinelBundle\Entity\BaseSiteLab;
use NS\SentinelBundle\Entity\Loggable\LogEvent;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Loggable\LoggableListener;

class OnFlushListener
{
    /**
     * @var LoggableListener
     */
    private $logger;

    /**
     * @var bool
     */
    private $recomputeChangeSet = false;

    /**
     * @var EntityManagerInterface
     */
    private $entityMgr;

    /**
     * OnFlushListener constructor.
     * @param LoggableListener $logger
     */
    public function __construct(LoggableListener $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $this->recomputeChangeSet = false;

        $this->entityMgr = $eventArgs->getEntityManager();

        $uow = $this->entityMgr->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof BaseCase || $entity instanceof BaseSiteLab || $entity instanceof BaseExternalLab) {
                $this->entityMgr->persist($this->logger->getLogEvent(LogEvent::CREATED, $uow->getSingleIdentifierValue($entity), $entity));
                $this->recomputeChangeSet = true;
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof BaseCase || $entity instanceof BaseSiteLab || $entity instanceof BaseExternalLab) {
                if ($entity instanceof BaseCase) {
                    $this->checkLinking($entity, $this->entityMgr);
                }
                $this->entityMgr->persist($this->logger->getLogEvent(LogEvent::UPDATED, $uow->getSingleIdentifierValue($entity), $entity));
                $this->recomputeChangeSet = true;
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof BaseCase) {
                $this->entityMgr->persist($this->logger->getLogEvent(LogEvent::DELETED, $uow->getSingleIdentifierValue($entity), $entity));
                $this->recomputeChangeSet = true;
            }
        }

        if ($this->recomputeChangeSet === true) {
            $this->entityMgr->getUnitOfWork()->computeChangeSets();
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

            $manager->persist($newCase);
            $manager->remove($case);
            $this->recomputeChangeSet = true;
        }
    }
}
