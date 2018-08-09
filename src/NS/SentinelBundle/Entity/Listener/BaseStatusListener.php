<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 27/07/18
 * Time: 8:31 AM
 */

namespace NS\SentinelBundle\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use NS\SentinelBundle\Form\Types\CaseStatus;

abstract class BaseStatusListener
{
    /**
     * @param BaseCase $case
     * @param LifecycleEventArgs $event
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function preUpdate($case, LifecycleEventArgs $event)
    {
        $this->calculateStatus($case);
        $case->setUpdatedAt(new \DateTime());
    }

    /**
     * @param $case
     * @param LifecycleEventArgs $event
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function prePersist($case, LifecycleEventArgs $event)
    {
        $this->calculateStatus($case);
        $case->setUpdatedAt(new \DateTime());
    }

    public function calculateStatus($case)
    {
        if ($case->getStatus()->equal(CaseStatus::CANCELLED)) {
            return;
        }

        $status = $this->getIncompleteField($case) ? new CaseStatus(CaseStatus::OPEN) : new CaseStatus(CaseStatus::COMPLETE);
        $case->setStatus($status);
    }

    abstract public function getIncompleteField($case);

    abstract protected function getMinimumRequiredFields($case, $regionCode = null);
}
