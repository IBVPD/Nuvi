<?php

namespace NS\SentinelBundle\Entity\Listener;

use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Form\Types\CaseStatus;

abstract class BaseStatusListener
{
    public function preUpdate(BaseCase $case, LifecycleEventArgs $event): void
    {
        $this->calculateStatus($case);
        $case->setUpdatedAt(new DateTime());
    }

    public function prePersist(BaseCase $case, LifecycleEventArgs $event): void
    {
        $this->calculateStatus($case);
        $case->setUpdatedAt(new DateTime());
    }

    public function calculateStatus($case): void
    {
        if ($case->getStatus()->equal(CaseStatus::CANCELLED)) {
            return;
        }

        $status = $this->getIncompleteField($case) ? new CaseStatus(CaseStatus::OPEN) : new CaseStatus(CaseStatus::COMPLETE);
        $case->setStatus($status);
    }

    abstract public function getIncompleteField($case);

    abstract protected function getMinimumRequiredFields($case, ?string $regionCode = null);
}
