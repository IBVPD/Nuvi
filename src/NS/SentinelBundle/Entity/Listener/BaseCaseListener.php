<?php

namespace NS\SentinelBundle\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Form\Types\CaseStatus;

/**
 * Class BaseCaseListener
 * @package NS\SentinelBundle\Entity\Listener
 */
abstract class BaseCaseListener
{
    /**
     * @param BaseCase $case
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(BaseCase $case, LifecycleEventArgs $event)
    {
        $this->calculateAge($case);
        $this->calculateStatus($case);
        $this->calculateResult($case);
        $case->setUpdatedAt(new \DateTime());

    }

    /**
     * @param BaseCase $case
     * @param LifecycleEventArgs $event
     */
    public function prePersist(BaseCase $case, LifecycleEventArgs $event)
    {
        $this->calculateAge($case);
        $this->calculateStatus($case);
        $this->calculateResult($case);
        $case->setUpdatedAt(new \DateTime());
    }

    /**
     * @param BaseCase $case
     */
    public function calculateAge(BaseCase $case)
    {
        if ($case->getDob() && $case->getAdmDate()) {
            $interval = $case->getDob()->diff($case->getAdmDate());
            $case->setAge(($interval->format('%a') / 30.5));
        } elseif ($case->getAdmDate() && !$case->getDob()) {
            if (!$case->getAge() && $case->getDobYears() !== null && $case->getDobMonths() !== null) {
                $case->setAge( (int) ( ($case->getDobYears() * 12) + $case->getDobMonths() ) );
            }

            if ($case->getAge() >= 0) {
                $date = clone $case->getAdmDate();
                $case->setDob($date->sub(new \DateInterval(sprintf('P%dM', (int)$case->getAge()))));
            }
        }

        if ($case->getAge() >= 0) {
            if ($case->getAge() < 6) {
                $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_00_TO_05);
            } elseif ($case->getAge() < 12) {
                $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_05_TO_11);
            } elseif ($case->getAge() < 24) {
                $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_11_TO_23);
            } elseif ($case->getAge() < 60) {
                $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_23_TO_59);
            } else {
                $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_UNKNOWN);
            }
        } else {
            $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_UNKNOWN);
        }
    }

    /**
     * @return null
     */
    public function calculateStatus(BaseCase $case)
    {
        if($case->getStatus()->equal(CaseStatus::CANCELLED)) {
            return;
        }

        $status = $this->getIncompleteField($case) ? new CaseStatus(CaseStatus::OPEN) :new CaseStatus(CaseStatus::COMPLETE);
        $case->setStatus($status);
    }

    /**
     * @return mixed
     */
    abstract public function getIncompleteField(BaseCase $case);

    /**
     * @return mixed
     */
    abstract public function getMinimumRequiredFields(BaseCase $case);

    /**
     * @return mixed
     */
    abstract public function calculateResult(BaseCase $case);
}
