<?php

namespace NS\SentinelBundle\Entity\Listener;

use DateInterval;
use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Form\Types\CaseStatus;

/**
 * Class BaseCaseListener
 * @package NS\SentinelBundle\Entity\Listener
 */
abstract class BaseCaseListener
{
    public function preUpdate(BaseCase $case, LifecycleEventArgs $event): void
    {
        $this->calculateAge($case);
        $this->calculateStatus($case);
        $this->calculateResult($case);
        $case->setUpdatedAt(new DateTime());
    }

    public function prePersist(BaseCase $case, LifecycleEventArgs $event): void
    {
        $this->calculateAge($case);
        $this->calculateStatus($case);
        $this->calculateResult($case);
        $case->setUpdatedAt(new DateTime());
    }

    public function calculateAge(BaseCase $case): void
    {
        if ($case->getDob() && $case->getAdmDate()) {
            $interval = $case->getDob()->diff($case->getAdmDate());
            $case->setAge($interval->format('%a') / 30.5);
        } elseif ($case->getAdmDate() && !$case->getDob()) {
            if (!$case->getAge() && $case->getDobYearMonths() !== null) {
                $case->setAge($case->getDobYearMonths()->getMonths());
            }

            if ($case->getAge() >= 0) {
                $date = clone $case->getAdmDate();
                $case->setDob($date->sub(new DateInterval(sprintf('P%dM', $case->getAge()))));
            }
        }

        if ($case->getAge() >= 0) {
            if ($case->getAge() <= 5) {
                $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_00_TO_05);
            } elseif ($case->getAge() <= 11) {
                $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_06_TO_11);
            } elseif ($case->getAge() <= 23) {
                $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_12_TO_23);
            } elseif ($case->getAge() <= 59) {
                $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_24_TO_59);
            } else {
                $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_UNKNOWN);
            }
        } else {
            $case->setAgeDistribution(BaseCase::AGE_DISTRIBUTION_UNKNOWN);
        }
    }

    public function calculateStatus(BaseCase $case): void
    {
        if ($case->getStatus()->equal(CaseStatus::CANCELLED)) {
            return;
        }

        $status = $this->getIncompleteField($case) ? new CaseStatus(CaseStatus::OPEN) : new CaseStatus(CaseStatus::COMPLETE);
        $case->setStatus($status);
    }

    abstract public function getIncompleteField(BaseCase $case): ?string;

    abstract public function getMinimumRequiredFields(BaseCase $case, ?string $regionCode = null): array;

    abstract public function calculateResult(BaseCase $case): void;
}
